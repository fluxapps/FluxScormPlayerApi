ARG FLUX_AUTOLOAD_API_IMAGE=docker-registry.fluxpublisher.ch/flux-autoload/api:latest
ARG FLUX_REST_API_IMAGE=docker-registry.fluxpublisher.ch/flux-rest/api:latest
ARG MONGODB_SOURCE_URL=https://pecl.php.net/get/mongodb
ARG MONGODBLIBRARY_SOURCE_URL=https://github.com/mongodb/mongo-php-library/archive/master.tar.gz
ARG PHP_CLI_IMAGE=php:cli-alpine
ARG SCORMAGAIN_SOURCE_URL=https://github.com/jcputney/scorm-again/archive/master.tar.gz
ARG SWOOLE_SOURCE_URL=https://github.com/swoole/swoole-src/archive/master.tar.gz

FROM $FLUX_AUTOLOAD_API_IMAGE AS flux_autoload_api
FROM $FLUX_REST_API_IMAGE AS flux_rest_api

FROM $PHP_CLI_IMAGE
ARG MONGODB_SOURCE_URL
ARG MONGODBLIBRARY_SOURCE_URL
ARG SCORMAGAIN_SOURCE_URL
ARG SWOOLE_SOURCE_URL

LABEL org.opencontainers.image.source="https://github.com/fluxapps/flux-scorm-player-api"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

RUN apk add --no-cache libstdc++ libzip && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS curl-dev libzip-dev openssl-dev && \
    (mkdir -p /usr/src/php/ext/mongodb && cd /usr/src/php/ext/mongodb && wget -O - $MONGODB_SOURCE_URL | tar -xz --strip-components=1) && \
    (mkdir -p /usr/src/php/ext/swoole && cd /usr/src/php/ext/swoole && wget -O - $SWOOLE_SOURCE_URL | tar -xz --strip-components=1) && \
    docker-php-ext-configure swoole --enable-openssl --enable-swoole-curl --enable-swoole-json && \
    docker-php-ext-install -j$(nproc) mongodb swoole zip && \
    docker-php-source delete && \
    apk del .build-deps

COPY --from=flux_autoload_api /flux-autoload-api /flux-scorm-player-api/libs/flux-autoload-api
COPY --from=flux_rest_api /flux-rest-api /flux-scorm-player-api/libs/flux-rest-api
RUN (mkdir -p /flux-scorm-player-api/libs/mongo-php-library && cd /flux-scorm-player-api/libs/mongo-php-library && wget -O - $MONGODBLIBRARY_SOURCE_URL | tar -xz --strip-components=1)
RUN (mkdir -p /flux-scorm-player-api/libs/_temp_scorm-again && cd /flux-scorm-player-api/libs/_temp_scorm-again && wget -O - $SCORMAGAIN_SOURCE_URL | tar -xz --strip-components=1 && rm -rf ../scorm-again && mv dist ../scorm-again && rm -rf ../_temp_scorm-again)
COPY . /flux-scorm-player-api

ENTRYPOINT ["/flux-scorm-player-api/bin/entrypoint.php"]

VOLUME /scorm

EXPOSE 9501
