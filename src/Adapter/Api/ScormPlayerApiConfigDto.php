<?php

namespace FluxScormPlayerApi\Adapter\Api;

use Exception;
use FluxScormPlayerApi\Adapter\Config\DatabaseConfigDto;
use FluxScormPlayerApi\Adapter\Config\DataStorageConfigDto;
use FluxScormPlayerApi\Adapter\Config\DataStorageConfigType;
use FluxScormPlayerApi\Adapter\Config\ExternalApiConfigDto;
use FluxScormPlayerApi\Adapter\Config\FilesystemConfigDto;
use FluxScormPlayerApi\Adapter\DataStorage\DatabaseDataStorage;
use FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerApi\Adapter\DataStorage\ExternalApiDataStorage;
use FluxScormPlayerApi\Adapter\MetadataStorage\DatabaseMetadataStorage;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;
use MongoDB\Client;

class ScormPlayerApiConfigDto
{

    private function __construct(
        public readonly FilesystemConfigDto $filesystem_config,
        public readonly MetadataStorage $metadata_storage,
        public readonly DataStorage $data_storage
    ) {

    }


    public static function new(
        FilesystemConfigDto $filesystem_config,
        MetadataStorage $metadata_storage,
        DataStorage $data_storage
    ) : static {
        return new static(
            $filesystem_config,
            $metadata_storage,
            $data_storage
        );
    }


    public static function newFromEnv() : static
    {
        $database_config = DatabaseConfigDto::newFromEnv();
        $database = (new Client("mongodb://" . $database_config->user . ":" . $database_config->password . "@" . $database_config->host . ":"
            . $database_config->port))->selectDatabase($database_config->database);

        $data_storage_config = DataStorageConfigDto::newFromEnv();
        switch ($data_storage_config->type) {
            case DataStorageConfigType::EXTERNAL_API:
                $data_storage = ExternalApiDataStorage::new(
                    ExternalApiConfigDto::newFromEnv()
                );
                break;

            case DataStorageConfigType::DATABASE:
                $data_storage = DatabaseDataStorage::newFromDatabase(
                    $database
                );
                break;

            default:
                throw new Exception("Unknown data storage type " . $data_storage_config->type->value);
        }

        return static::new(
            FilesystemConfigDto::newFromEnv(),
            DatabaseMetadataStorage::newFromDatabase(
                $database
            ),
            $data_storage
        );
    }
}
