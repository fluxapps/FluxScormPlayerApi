ARG REST_API_IMAGE
FROM $REST_API_IMAGE AS rest_api

FROM phpswoole/swoole:latest-alpine

LABEL org.opencontainers.image.source="https://github.com/fluxapps/flux-scorm-player-api"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

RUN apk add --no-cache libzip-dev openssl-dev && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    docker-php-ext-install zip && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    docker-php-source delete && \
    apk del .build-deps

COPY --from=rest_api /flux-rest-api /flux-scorm-player-api/libs/flux-rest-api
RUN (mkdir -p /flux-scorm-player-api/libs/mongo-php-library && cd /flux-scorm-player-api/libs/mongo-php-library && wget -O - https://github.com/mongodb/mongo-php-library/archive/master.tar.gz | tar -xz --strip-components=1)
RUN (mkdir -p /flux-scorm-player-api/libs/_temp_scorm-again && cd /flux-scorm-player-api/libs/_temp_scorm-again && wget -O - https://github.com/jcputney/scorm-again/archive/master.tar.gz | tar -xz --strip-components=1 && rm -rf ../scorm-again && mv dist ../scorm-again && rm -rf ../_temp_scorm-again)
COPY . /flux-scorm-player-api

ENTRYPOINT ["/flux-scorm-player-api/bin/entrypoint.php"]

VOLUME /scorm

EXPOSE 9501
