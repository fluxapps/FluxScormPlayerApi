<?php

namespace Fluxlabs\FluxScormPlayerApi\Adapter\Config;

use Fluxlabs\FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use Fluxlabs\FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;

interface Config
{

    public function getDataStorage() : DataStorage;


    public function getDataStorageConfig() : DataStorageConfigDto;


    public function getDatabaseConfig() : DatabaseConfigDto;


    public function getExternalApiConfig() : ExternalApiConfigDto;


    public function getFilesystemConfig() : FilesystemConfigDto;


    public function getMetadataStorage() : MetadataStorage;


    public function getServerConfig() : ServerConfigDto;
}
