<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command;

use Fluxlabs\FluxScormPlayerApi\Adapter\Config\FilesystemConfigDto;
use Fluxlabs\FluxScormPlayerApi\Adapter\MetadataStorage\MetadataDto;
use Fluxlabs\FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;

class GetScormPackageMetadataCommand
{

    private FilesystemConfigDto $filesystem_config;
    private MetadataStorage $metadata_storage;


    public static function new(FilesystemConfigDto $filesystem_config, MetadataStorage $metadata_storage) : static
    {
        $command = new static();

        $command->filesystem_config = $filesystem_config;
        $command->metadata_storage = $metadata_storage;

        return $command;
    }


    public function getScormPackageMetadata(string $id) : ?MetadataDto
    {
        if (!file_exists($this->filesystem_config->getFolder() . "/" . $id)) {
            return null;
        }

        return $this->metadata_storage->getMetadata(
            $id
        );
    }
}
