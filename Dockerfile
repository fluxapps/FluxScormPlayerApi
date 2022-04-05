ARG ALPINE_IMAGE=alpine:latest
ARG FLUX_AUTOLOAD_API_IMAGE=docker-registry.fluxpublisher.ch/flux-autoload/api:latest
ARG FLUX_FILE_STORAGE_API=docker-registry.fluxpublisher.ch/flux-file-storage/api:latest
ARG FLUX_NAMESPACE_CHANGER_IMAGE=docker-registry.fluxpublisher.ch/flux-namespace-changer:latest
ARG FLUX_REST_API_IMAGE=docker-registry.fluxpublisher.ch/flux-rest/api:latest
ARG MONGODBLIBRARY_SOURCE_URL=https://github.com/mongodb/mongo-php-library/archive/master.tar.gz
ARG SCORMAGAIN_SOURCE_URL=https://github.com/jcputney/scorm-again/archive/master.tar.gz

FROM $FLUX_AUTOLOAD_API_IMAGE AS flux_autoload_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE AS flux_autoload_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxAutoloadApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxScormPlayerApi\\Libs\\FluxAutoloadApi
COPY --from=flux_autoload_api /flux-autoload-api /code
RUN $FLUX_NAMESPACE_CHANGER_BIN

FROM $FLUX_FILE_STORAGE_API AS flux_file_storage_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE AS flux_file_storage_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxFileStorageApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxScormPlayerApi\\Libs\\FluxFileStorageApi
COPY --from=flux_file_storage_api /flux-file-storage-api /code
RUN $FLUX_NAMESPACE_CHANGER_BIN

FROM $FLUX_REST_API_IMAGE AS flux_rest_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE AS flux_rest_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxRestApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxScormPlayerApi\\Libs\\FluxRestApi
COPY --from=flux_rest_api /flux-rest-api /code
RUN $FLUX_NAMESPACE_CHANGER_BIN

FROM $ALPINE_IMAGE AS build
ARG MONGODBLIBRARY_SOURCE_URL
ARG SCORMAGAIN_SOURCE_URL

COPY --from=flux_autoload_api_build /code /flux-scorm-player-api/libs/flux-autoload-api
COPY --from=flux_file_storage_api_build /code /flux-scorm-player-api/libs/flux-file-storage-api
COPY --from=flux_rest_api_build /code /flux-scorm-player-api/libs/flux-rest-api
RUN (mkdir -p /flux-scorm-player-api/libs/mongo-php-library && cd /flux-scorm-player-api/libs/mongo-php-library && wget -O - $MONGODBLIBRARY_SOURCE_URL | tar -xz --strip-components=1)
RUN (mkdir -p /flux-scorm-player-api/libs/_temp_scorm-again && cd /flux-scorm-player-api/libs/_temp_scorm-again && wget -O - $SCORMAGAIN_SOURCE_URL | tar -xz --strip-components=1 && rm -rf ../scorm-again && mv dist ../scorm-again && rm -rf ../_temp_scorm-again)
COPY . /flux-scorm-player-api

FROM scratch

LABEL org.opencontainers.image.source="https://github.com/flux-eco/flux-scorm-player-api"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

COPY --from=build /flux-scorm-player-api /flux-scorm-player-api
