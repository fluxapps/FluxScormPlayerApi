ARG FLUX_AUTOLOAD_API_IMAGE
ARG FLUX_FILE_STORAGE_API_IMAGE
ARG FLUX_NAMESPACE_CHANGER_IMAGE=docker-registry.fluxpublisher.ch/flux-namespace-changer
ARG FLUX_REST_API_IMAGE

FROM $FLUX_AUTOLOAD_API_IMAGE:v2022-06-22-1 AS flux_autoload_api
FROM $FLUX_FILE_STORAGE_API_IMAGE:v2022-07-05-1 AS flux_file_storage_api
FROM $FLUX_REST_API_IMAGE:v2022-07-11-1 AS flux_rest_api

FROM composer:latest AS composer

RUN (mkdir -p /code/mongo-php-library && cd /code/mongo-php-library && composer require mongodb/mongodb:1.12.0 --ignore-platform-reqs && rm -rf vendor/mongodb/mongodb/tests)

FROM node:current-alpine AS npm

RUN (mkdir -p /code/scorm-again && cd /code/scorm-again && npm install scorm-again@1.7.0)

FROM $FLUX_NAMESPACE_CHANGER_IMAGE:v2022-06-23-1 AS build_namespaces

COPY --from=flux_autoload_api /flux-autoload-api /code/flux-autoload-api
RUN change-namespace /code/flux-autoload-api FluxAutoloadApi FluxScormPlayerApi\\Libs\\FluxAutoloadApi

COPY --from=flux_file_storage_api /flux-file-storage-api /code/flux-file-storage-api
RUN change-namespace /code/flux-file-storage-api FluxFileStorageApi FluxScormPlayerApi\\Libs\\FluxFileStorageApi

COPY --from=flux_rest_api /flux-rest-api /code/flux-rest-api
RUN change-namespace /code/flux-rest-api FluxRestApi FluxScormPlayerApi\\Libs\\FluxRestApi

FROM alpine:latest AS build

COPY --from=build_namespaces /code/flux-autoload-api /build/flux-scorm-player-api/libs/flux-autoload-api
COPY --from=build_namespaces /code/flux-file-storage-api /build/flux-scorm-player-api/libs/flux-file-storage-api
COPY --from=build_namespaces /code/flux-rest-api /build/flux-scorm-player-api/libs/flux-rest-api
COPY --from=composer /code/mongo-php-library /build/flux-scorm-player-api/libs/mongo-php-library
COPY --from=npm /code/scorm-again /build/flux-scorm-player-api/libs/scorm-again
COPY . /build/flux-scorm-player-api

RUN (cd /build && tar -czf flux-scorm-player-api.tar.gz flux-scorm-player-api)

FROM scratch

LABEL org.opencontainers.image.source="https://github.com/flux-eco/flux-scorm-player-api"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

COPY --from=build /build /

ARG COMMIT_SHA
LABEL org.opencontainers.image.revision="$COMMIT_SHA"
