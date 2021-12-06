<?php

namespace FluxScormPlayerApi\Channel\Filesystem\Command;

use FluxScormPlayerApi\Adapter\Config\FilesystemConfigDto;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataDto;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;

class GetScormPackageMetadataCommand
{

    private readonly FilesystemConfigDto $filesystem_config;
    private readonly MetadataStorage $metadata_storage;


    public static function new(FilesystemConfigDto $filesystem_config, MetadataStorage $metadata_storage) : static
    {
        $command = new static();

        $command->filesystem_config = $filesystem_config;
        $command->metadata_storage = $metadata_storage;

        return $command;
    }


    public function getScormPackageMetadata(string $id) : ?MetadataDto
    {
        if (!file_exists($this->filesystem_config->folder . "/" . $id)) {
            return null;
        }

        return $this->metadata_storage->getMetadata(
            $id
        );
    }
}
