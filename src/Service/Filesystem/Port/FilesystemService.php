<?php

namespace FluxScormPlayerApi\Service\Filesystem\Port;

use FluxFileStorageApi\Adapter\Api\FileStorageApi;
use FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataDto;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;
use FluxScormPlayerApi\Service\Filesystem\Command\DeleteScormPackageCommand;
use FluxScormPlayerApi\Service\Filesystem\Command\GetScormPackageAssetPathCommand;
use FluxScormPlayerApi\Service\Filesystem\Command\GetScormPackageMetadataCommand;
use FluxScormPlayerApi\Service\Filesystem\Command\UploadScormPackageCommand;

class FilesystemService
{

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
        DeleteScormPackageCommand::new(
            $this->file_storage_api,
            $this->metadata_storage,
            $this->data_storage
        )
            ->deleteScormPackage(
                $id
            );
    }


    public function getScormPackageAssetPath(string $id, string $path) : ?string
    {
        return GetScormPackageAssetPathCommand::new(
            $this->file_storage_api
        )
            ->getScormPackageAssetPath(
                $id,
                $path
            );
    }


    public function getScormPackageMetadata(string $id) : ?MetadataDto
    {
        return GetScormPackageMetadataCommand::new(
            $this->file_storage_api,
            $this->metadata_storage
        )
            ->getScormPackageMetadata(
                $id
            );
    }


    public function uploadScormPackage(string $id, string $title, string $file) : void
    {
        UploadScormPackageCommand::new(
            $this->file_storage_api,
            $this->metadata_storage
        )
            ->uploadScormPackage(
                $id,
                $title,
                $file
            );
    }
}
