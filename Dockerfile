FROM node:current-alpine AS npm

RUN (mkdir -p /build/flux-scorm-player-api/libs/scorm-again && cd /build/flux-scorm-player-api/libs/scorm-again && npm install scorm-again@1.7.0)

FROM composer:latest AS build

RUN (mkdir -p /flux-namespace-changer && cd /flux-namespace-changer && wget -O - https://github.com/fluxfw/flux-namespace-changer/releases/download/v2022-07-12-1/flux-namespace-changer-v2022-07-12-1-build.tar.gz | tar -xz --strip-components=1)

RUN (mkdir -p /build/flux-scorm-player-api/libs/mongo-php-library && cd /build/flux-scorm-player-api/libs/mongo-php-library && composer require mongodb/mongodb:1.12.0 --ignore-platform-reqs && cd vendor/mongodb/mongodb && rm -rf $(ls -A -I "composer*" -I "LICENSE*" -I src))

COPY --from=npm /build/flux-scorm-player-api/libs/scorm-again /build/flux-scorm-player-api/libs/scorm-again
RUN (cd /build/flux-scorm-player-api/libs/scorm-again/node_modules/scorm-again && rm -rf $(ls -A -I dist -I "LICENSE*" -I "package*") && cd dist && rm -rf $(ls -A -I "*.min.js"))

RUN (mkdir -p /build/flux-scorm-player-api/libs/flux-autoload-api && cd /build/flux-scorm-player-api/libs/flux-autoload-api && wget -O - https://github.com/fluxfw/flux-autoload-api/releases/download/v2022-07-12-1/flux-autoload-api-v2022-07-12-1-build.tar.gz | tar -xz --strip-components=1 && /flux-namespace-changer/bin/change-namespace.php . FluxAutoloadApi FluxScormPlayerApi\\Libs\\FluxAutoloadApi)

RUN (mkdir -p /build/flux-scorm-player-api/libs/flux-file-storage-api && cd /build/flux-scorm-player-api/libs/flux-file-storage-api && wget -O - https://github.com/fluxfw/flux-file-storage-api/releases/download/v2022-07-12-1/flux-file-storage-api-v2022-07-12-1-build.tar.gz | tar -xz --strip-components=1 && /flux-namespace-changer/bin/change-namespace.php . FluxFileStorageApi FluxScormPlayerApi\\Libs\\FluxFileStorageApi)

RUN (mkdir -p /build/flux-scorm-player-api/libs/flux-rest-api && cd /build/flux-scorm-player-api/libs/flux-rest-api && wget -O - https://github.com/fluxfw/flux-rest-api/releases/download/v2022-07-12-1/flux-rest-api-v2022-07-12-1-build.tar.gz | tar -xz --strip-components=1 && /flux-namespace-changer/bin/change-namespace.php . FluxRestApi FluxScormPlayerApi\\Libs\\FluxRestApi)

COPY . /build/flux-scorm-player-api

FROM scratch

COPY --from=build /build /
