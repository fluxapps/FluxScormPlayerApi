ARG FLUX_AUTOLOAD_API_IMAGE=docker-registry.fluxpublisher.ch/flux-autoload/api
ARG FLUX_FILE_STORAGE_API_IMAGE=docker-registry.fluxpublisher.ch/flux-file-storage/api
ARG FLUX_NAMESPACE_CHANGER_IMAGE=docker-registry.fluxpublisher.ch/flux-namespace-changer
ARG FLUX_REST_API_IMAGE=docker-registry.fluxpublisher.ch/flux-rest/api

FROM $FLUX_AUTOLOAD_API_IMAGE:latest AS flux_autoload_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE:latest AS flux_autoload_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxAutoloadApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxScormPlayerApi\\Libs\\FluxAutoloadApi
COPY --from=flux_autoload_api /flux-autoload-api /code
RUN change-namespace

FROM $FLUX_FILE_STORAGE_API_IMAGE:latest AS flux_file_storage_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE:latest AS flux_file_storage_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxFileStorageApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxScormPlayerApi\\Libs\\FluxFileStorageApi
COPY --from=flux_file_storage_api /flux-file-storage-api /code
RUN change-namespace

FROM $FLUX_REST_API_IMAGE:latest AS flux_rest_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE:latest AS flux_rest_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxRestApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxScormPlayerApi\\Libs\\FluxRestApi
COPY --from=flux_rest_api /flux-rest-api /code
RUN change-namespace

FROM alpine:latest AS build

COPY --from=flux_autoload_api_build /code /flux-scorm-player-api/libs/flux-autoload-api
COPY --from=flux_file_storage_api_build /code /flux-scorm-player-api/libs/flux-file-storage-api
COPY --from=flux_rest_api_build /code /flux-scorm-player-api/libs/flux-rest-api
RUN (mkdir -p /flux-scorm-player-api/libs/mongo-php-library && cd /flux-scorm-player-api/libs/mongo-php-library && wget -O - https://github.com/mongodb/mongo-php-library/archive/master.tar.gz | tar -xz --strip-components=1)
RUN (mkdir -p /flux-scorm-player-api/libs/_temp_scorm-again && cd /flux-scorm-player-api/libs/_temp_scorm-again && wget -O - https://github.com/jcputney/scorm-again/archive/master.tar.gz | tar -xz --strip-components=1 && rm -rf ../scorm-again && mv dist ../scorm-again && rm -rf ../_temp_scorm-again)
COPY . /flux-scorm-player-api

FROM scratch

LABEL org.opencontainers.image.source="https://github.com/flux-eco/flux-scorm-player-api"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

COPY --from=build /flux-scorm-player-api /flux-scorm-player-api

ARG COMMIT_SHA
LABEL org.opencontainers.image.revision="$COMMIT_SHA"
