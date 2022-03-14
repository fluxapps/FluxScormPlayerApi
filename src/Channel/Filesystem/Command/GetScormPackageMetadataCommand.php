<?php

namespace FluxScormPlayerApi\Channel\Filesystem\Command;

use FluxScormPlayerApi\Adapter\Config\FilesystemConfigDto;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataDto;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;

class GetScormPackageMetadataCommand
{

    private function __construct(
        private readonly FilesystemConfigDto $filesystem_config,
        private readonly MetadataStorage $metadata_storage
    ) {

    }


    public static function new(
        FilesystemConfigDto $filesystem_config,
        MetadataStorage $metadata_storage
    ) : static {
        return new static(
            $filesystem_config,
            $metadata_storage
        );
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
