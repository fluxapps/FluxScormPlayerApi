<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\GetScormPackageMetadata;

use Fluxlabs\FluxScormPlayerApi\Adapter\Config\FilesystemConfigDto;
use Fluxlabs\FluxScormPlayerApi\Adapter\MetadataStorage\MetadataDto;
use Fluxlabs\FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;

class GetScormPackageMetadataCommandHandler
{

    private FilesystemConfigDto $filesystem_config;
    private MetadataStorage $metadata_storage;


    public static function new(FilesystemConfigDto $filesystem_config, MetadataStorage $metadata_storage) : static
    {
        $handler = new static();

        $handler->filesystem_config = $filesystem_config;
        $handler->metadata_storage = $metadata_storage;

        return $handler;
    }


    public function handle(GetScormPackageMetadataCommand $command) : ?MetadataDto
    {
        if (!file_exists($this->filesystem_config->getFolder() . "/" . $command->getId())) {
            return null;
        }

        return $this->metadata_storage->getMetadata(
            $command->getId()
        );
    }
}
