FROM node:current-alpine AS build_node_modules

WORKDIR /build

COPY package*.json ./
RUN npm ci

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

COPY . /app
ENTRYPOINT ["/app/bin/entrypoint.php"]

COPY --from=build_node_modules /build/node_modules /app/node_modules

RUN composer install -d /app --no-dev

VOLUME /scorm

EXPOSE 9501
