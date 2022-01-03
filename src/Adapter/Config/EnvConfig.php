<?php

namespace FluxScormPlayerApi\Adapter\Config;

use Exception;
use FluxScormPlayerApi\Adapter\DataStorage\DatabaseDataStorage;
use FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerApi\Adapter\DataStorage\ExternalApiDataStorage;
use FluxScormPlayerApi\Adapter\MetadataStorage\DatabaseMetadataStorage;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Database;

class EnvConfig implements Config
{

    private static self $instance;
    private readonly Collection $data_collection;
    private readonly DataStorage $data_storage;
    private readonly DataStorageConfigDto $data_storage_config;
    private readonly Database $database;
    private readonly DatabaseConfigDto $database_config;
    private readonly ExternalApiConfigDto $external_api_config;
    private readonly FilesystemConfigDto $filesystem_config;
    private readonly Collection $metadata_collection;
    private readonly MetadataStorage $metadata_storage;
    private readonly ServerConfigDto $server_config;


    public static function new() : static
    {
        static::$instance ??= new static();

        return static::$instance;
    }


    public function getDataStorage() : DataStorage
    {
        switch ($this->getDataStorageConfig()->type) {
            case DataStorageConfigType::DATABASE:
                $this->data_storage ??= DatabaseDataStorage::new(
                    $this->getDataCollection()
                );
                break;

            case DataStorageConfigType::EXTERNAL_API:
                $this->data_storage ??= ExternalApiDataStorage::new(
                    $this->getExternalApiConfig()
                );
                break;

            default:
                throw new Exception("Unknown data storage type " . $this->getDataStorageConfig()->type->value);
        }

        return $this->data_storage;
    }


    public function getDataStorageConfig() : DataStorageConfigDto
    {
        $this->data_storage_config ??= DataStorageConfigDto::new(
            ($type = $_ENV["FLUX_SCORM_PLAYER_API_DATA_STORAGE_TYPE"] ?? null) !== null ? DataStorageConfigType::from($type) : null
        );

        return $this->data_storage_config;
    }


    public function getDatabaseConfig() : DatabaseConfigDto
    {
        $this->database_config ??= DatabaseConfigDto::new(
            ($_ENV["FLUX_SCORM_PLAYER_API_DATABASE_PASSWORD"] ?? null) ??
            ($password_file = $_ENV["FLUX_SCORM_PLAYER_API_DATABASE_PASSWORD_FILE"] ?? null) !== null && file_exists($password_file) ? (file_get_contents($password_file) ?: "") : null,
            $_ENV["FLUX_SCORM_PLAYER_API_DATABASE_HOST"] ?? null,
            $_ENV["FLUX_SCORM_PLAYER_API_DATABASE_PORT"] ?? null,
            $_ENV["FLUX_SCORM_PLAYER_API_DATABASE_USER"] ?? null,
            $_ENV["FLUX_SCORM_PLAYER_API_DATABASE_DATABASE"] ?? null
        );

        return $this->database_config;
    }


    public function getExternalApiConfig() : ExternalApiConfigDto
    {
        $this->external_api_config ??= ExternalApiConfigDto::new(
            $_ENV["FLUX_SCORM_PLAYER_API_EXTERNAL_API_GET_DATA_URL"],
            $_ENV["FLUX_SCORM_PLAYER_API_EXTERNAL_API_STORE_DATA_URL"],
            $_ENV["FLUX_SCORM_PLAYER_API_EXTERNAL_API_DELETE_DATA_URL"]
        );

        return $this->external_api_config;
    }


    public function getFilesystemConfig() : FilesystemConfigDto
    {
        $this->filesystem_config ??= FilesystemConfigDto::new(
            $_ENV["FLUX_SCORM_PLAYER_API_FILESYSTEM_FOLDER"] ?? null
        );

        return $this->filesystem_config;
    }


    public function getMetadataStorage() : MetadataStorage
    {
        $this->metadata_storage ??= DatabaseMetadataStorage::new(
            $this->getMetadataCollection()
        );

        return $this->metadata_storage;
    }


    public function getServerConfig() : ServerConfigDto
    {
        $this->server_config ??= ServerConfigDto::new(
            $_ENV["FLUX_SCORM_PLAYER_API_SERVER_HTTPS_CERT"] ?? null,
            $_ENV["FLUX_SCORM_PLAYER_API_SERVER_HTTPS_KEY"] ?? null,
            $_ENV["FLUX_SCORM_PLAYER_API_SERVER_LISTEN"] ?? null,
            $_ENV["FLUX_SCORM_PLAYER_API_SERVER_PORT"] ?? null,
            $_ENV["FLUX_SCORM_PLAYER_API_SERVER_MAX_UPLOAD_SIZE"] ?? null
        );

        return $this->server_config;
    }


    private function getDataCollection() : Collection
    {
        $this->data_collection ??= $this->getDatabase()->selectCollection("data");

        return $this->data_collection;
    }


    private function getDatabase() : Database
    {
        $this->database ??= (new Client("mongodb://" . $this->getDatabaseConfig()->user . ":" . $this->getDatabaseConfig()->password . "@" . $this->getDatabaseConfig()->host . ":"
            . $this->getDatabaseConfig()->port))->selectDatabase($this->getDatabaseConfig()->database);

        return $this->database;
    }


    private function getMetadataCollection() : Collection
    {
        $this->metadata_collection ??= $this->getDatabase()->selectCollection("metadata");

        return $this->metadata_collection;
    }
}
