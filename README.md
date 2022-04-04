# flux-scorm-player-api

Scorm Player Api for play scorm modules

## Installation

```dockerfile
COPY --from=docker-registry.fluxpublisher.ch/flux-scorm-player/api:latest /flux-scorm-player-api /%path%/libs/flux-scorm-player-api
```

## Usage

```php
require_once __DIR__ . "/%path%/libs/flux-scorm-player-api/autoload.php";
```

```php
ScormPlayerApi::new();
```

## Environment variables

| Variable | Description | Default value |
| -------- | ----------- | ------------- |
| FLUX_SCORM_PLAYER_API_FILESYSTEM_FOLDER | Scorm directory | /scorm |
| **FLUX_SCORM_PLAYER_API_DATABASE_PASSWORD** | MongoDB password<br>Use *FLUX_SCORM_PLAYER_API_DATABASE_PASSWORD_FILE* for docker secrets | - |
| FLUX_SCORM_PLAYER_API_DATABASE_HOST | MongoDB host | scorm-player-database |
| FLUX_SCORM_PLAYER_API_DATABASE_PORT | MongoDB port | 27017 |
| FLUX_SCORM_PLAYER_API_DATABASE_USER | MongoDB user name | scorm-player |
| FLUX_SCORM_PLAYER_API_DATABASE_DATABASE | MongoDB database name | scorm-player |
| FLUX_SCORM_PLAYER_API_DATA_STORAGE_TYPE | Data storage type<br>database or external_api | database |
| FLUX_SCORM_PLAYER_API_EXTERNAL_API_GET_DATA_URL | External api data storage get url<br>You can use {scorm_id} and {user_id} placeholders | - |
| FLUX_SCORM_PLAYER_API_EXTERNAL_API_STORE_DATA_URL | External api data storage store url<br>You can use {scorm_id} and {user_id} placeholders | - |
| FLUX_SCORM_PLAYER_API_EXTERNAL_API_DELETE_DATA_URL | External api data storage delete url<br>You can use {scorm_id} placeholder | - |

Minimal variables required to set are **bold**

## Example

Look at [flux-scorm-player-rest-api](https://github.com/flux-caps/flux-scorm-player-rest-api)
