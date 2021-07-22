FROM node:16-alpine AS build_node_modules

WORKDIR /app

COPY package*.json ./
RUN npm ci

FROM phpswoole/swoole:4.7-php8.0-alpine

LABEL org.opencontainers.image.source="https://github.com/fluxapps/FluxScormPlayerApi"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

RUN apk add --no-cache libzip-dev openssl-dev && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    docker-php-ext-install zip && \
    pecl install mongodb && \
    docker-php-ext-enable mongodb && \
    docker-php-source delete && \
    apk del .build-deps

WORKDIR /app

COPY . .

COPY --from=build_node_modules /app/node_modules node_modules

RUN composer install --no-dev

WORKDIR bin

VOLUME /scorm

EXPOSE 9501

RUN chmod +x entrypoint.php
ENTRYPOINT ["./entrypoint.php"]
