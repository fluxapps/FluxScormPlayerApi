<?php

namespace FluxScormPlayerApi\Channel\Filesystem\Command;

use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataDto;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;
use FluxScormPlayerApi\Channel\Filesystem\FilesystemUtils;
use FluxScormPlayerApi\Libs\FluxFileStorageApi\Adapter\Api\FileStorageApi;

class GetScormPackageMetadataCommand
{

    use FilesystemUtils;

    private function __construct(
        private readonly FileStorageApi $file_storage_api,
        private readonly MetadataStorage $metadata_storage
    ) {

    }


    public static function new(
        FileStorageApi $file_storage_api,
        MetadataStorage $metadata_storage
    ) : static {
        return new static(
            $file_storage_api,
            $metadata_storage
        );
    }


    public function getScormPackageMetadata(string $id) : ?MetadataDto
    {
        $id = $this->normalizeId(
            $id
        );

        if (!$this->file_storage_api->exists(
            $id
        )
        ) {
            return null;
        }

        return $this->metadata_storage->getMetadata(
            $id
        );
    }
}
