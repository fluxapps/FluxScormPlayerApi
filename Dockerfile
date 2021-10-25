FROM phpswoole/swoole:4.8-php8.0-alpine

LABEL org.opencontainers.image.source="https://github.com/fluxapps/FluxScormPlayerApi"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

RUN apk add --no-cache libzip-dev openssl-dev && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    docker-php-ext-install zip && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    docker-php-source delete && \
    apk del .build-deps

COPY . /FluxScormPlayerApi
RUN (mkdir -p /tmp/scorm-again && cd /tmp/scorm-again && wget -O - https://github.com/jcputney/scorm-again/archive/master.tar.gz | tar -xz --strip-components=1 && mkdir -p /FluxScormPlayerApi/libs && mv dist /FluxScormPlayerApi/libs/scorm-again && rm -rf /tmp/scorm-again)

ENTRYPOINT ["/FluxScormPlayerApi/bin/entrypoint.php"]

RUN composer install -d /FluxScormPlayerApi --no-dev

VOLUME /scorm

EXPOSE 9501
