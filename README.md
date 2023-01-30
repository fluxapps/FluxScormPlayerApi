# flux-scorm-player-api

Scorm Player Api for play scorm modules

## Installation

### Native

#### Download

```dockerfile
RUN (mkdir -p /%path%/libs/flux-scorm-player-api && cd /%path%/libs/flux-scorm-player-api && wget -O - https://github.com/fluxfw/flux-scorm-player-api/archive/refs/tags/%tag%.tar.gz | tar -xz --strip-components=1)
```

or

Download https://github.com/fluxfw/flux-scorm-player-api/archive/refs/tags/%tag%.tar.gz and extract it to `/%path%/libs/flux-scorm-player-api`

#### Load

```php
require_once __DIR__ . "/%path%/libs/flux-scorm-player-api/autoload.php";
```

### Composer

```json
{
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "flux/flux-scorm-player-api",
                "version": "%tag%",
                "dist": {
                    "url": "https://github.com/fluxfw/flux-scorm-player-api/archive/refs/tags/%tag%.tar.gz",
                    "type": "tar"
                },
                "autoload": {
                    "files": [
                        "autoload.php"
                    ]
                }
            }
        }
    ],
    "require": {
        "flux/flux-scorm-player-api": "*"
    }
}
```

## Environment variables

| Variable | Description | Default value |
| -------- | ----------- | ------------- |
| FLUX_SCORM_PLAYER_API_FILESYSTEM_FOLDER | Scorm directory | /scorm |
| **FLUX_SCORM_PLAYER_API_DATABASE_PASSWORD** | MongoDB password<br>Use *FLUX_SCORM_PLAYER_API_DATABASE_PASSWORD_FILE* for docker secrets | *-* |
| FLUX_SCORM_PLAYER_API_DATABASE_HOST | MongoDB host | scorm-player-database |
| FLUX_SCORM_PLAYER_API_DATABASE_PORT | MongoDB port | 27017 |
| FLUX_SCORM_PLAYER_API_DATABASE_USER | MongoDB user name | scorm-player |
| FLUX_SCORM_PLAYER_API_DATABASE_DATABASE | MongoDB database name | scorm-player |
| FLUX_SCORM_PLAYER_API_DATA_STORAGE_TYPE | Data storage type<br>database or external_api | database |
| FLUX_SCORM_PLAYER_API_EXTERNAL_API_GET_DATA_URL | External api data storage get url<br>You can use {scorm_id} and {user_id} placeholders | *-* |
| FLUX_SCORM_PLAYER_API_EXTERNAL_API_STORE_DATA_URL | External api data storage store url<br>You can use {scorm_id} and {user_id} placeholders | *-* |
| FLUX_SCORM_PLAYER_API_EXTERNAL_API_DELETE_DATA_URL | External api data storage delete url<br>You can use {scorm_id} placeholder | *-* |

Minimal variables required to set are **bold**

## Example

Look at [flux-scorm-player-rest-api](https://github.com/fluxfw/flux-scorm-player-rest-api)
