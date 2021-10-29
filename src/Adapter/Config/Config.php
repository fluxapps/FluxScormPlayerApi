<?php

namespace FluxScormPlayerApi\Adapter\Config;

use FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;

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
