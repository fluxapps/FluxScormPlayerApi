<?php

namespace FluxScormPlayerApi\Channel\Filesystem\Command;

use FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;
use FluxScormPlayerApi\Channel\Filesystem\FilesystemUtils;
use FluxScormPlayerApi\Libs\FluxFileStorageApi\Adapter\Api\FileStorageApi;

class DeleteScormPackageCommand
{

    use FilesystemUtils;

    private function __construct(
        private readonly FileStorageApi $file_storage_api,
        private readonly MetadataStorage $metadata_storage,
        private readonly DataStorage $data_storage
    ) {

    }


    public static function new(
        FileStorageApi $file_storage_api,
        MetadataStorage $metadata_storage,
        DataStorage $data_storage
    ) : static {
        return new static(
            $file_storage_api,
            $metadata_storage,
            $data_storage
        );
    }


    public function deleteScormPackage(string $id) : void
    {
        $id = $this->normalizeId(
            $id
        );

        $this->file_storage_api->delete(
            $id
        );

        $this->metadata_storage->deleteMetadata(
            $id
        );

        $this->data_storage->deleteData(
            $id
        );
    }
}
