FROM phpswoole/swoole:php8.0-alpine

LABEL org.opencontainers.image.source="https://github.com/fluxapps/FluxScormPlayerApi"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

RUN apk add --no-cache libzip-dev openssl-dev && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    docker-php-ext-install zip && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    docker-php-source delete && \
    apk del .build-deps

COPY --from=docker-registry.fluxpublisher.ch/flux-rest/api:latest /FluxRestApi /FluxScormPlayerApi/libs/FluxRestApi
RUN (mkdir -p /FluxScormPlayerApi/libs/mongo-php-library && cd /FluxScormPlayerApi/libs/mongo-php-library && wget -O - https://github.com/mongodb/mongo-php-library/archive/master.tar.gz | tar -xz --strip-components=1)
RUN (mkdir -p /FluxScormPlayerApi/libs/_temp_scorm-again && cd /FluxScormPlayerApi/libs/_temp_scorm-again && wget -O - https://github.com/jcputney/scorm-again/archive/master.tar.gz | tar -xz --strip-components=1 && rm -rf ../scorm-again && mv dist ../scorm-again && rm -rf ../_temp_scorm-again)
COPY . /FluxScormPlayerApi

ENTRYPOINT ["/FluxScormPlayerApi/bin/entrypoint.php"]

VOLUME /scorm

EXPOSE 9501
