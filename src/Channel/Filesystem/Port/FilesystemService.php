<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Port;

use Fluxlabs\FluxScormPlayerApi\Adapter\Config\FilesystemConfigDto;
use Fluxlabs\FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use Fluxlabs\FluxScormPlayerApi\Adapter\MetadataStorage\MetadataDto;
use Fluxlabs\FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\DeleteScormPackageCommand;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\GetScormPackageAssetPathCommand;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\GetScormPackageMetadataCommand;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\UploadScormPackageCommand;

class FilesystemService
{

    private DataStorage $data_storage;
    private FilesystemConfigDto $filesystem_config;
    private MetadataStorage $metadata_storage;


    public static function new(FilesystemConfigDto $filesystem_config, MetadataStorage $metadata_storage, DataStorage $data_storage) : static
    {
        $service = new static();

        $service->filesystem_config = $filesystem_config;
        $service->metadata_storage = $metadata_storage;
        $service->data_storage = $data_storage;

        return $service;
    }


    public function deleteScormPackage(string $id) : void
    {
        DeleteScormPackageCommand::new(
            $this->filesystem_config,
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
            $this->filesystem_config
        )
            ->getScormPackageAssetPath(
                $id,
                $path
            );
    }


    public function getScormPackageMetadata(string $id) : ?MetadataDto
    {
        return GetScormPackageMetadataCommand::new(
            $this->filesystem_config,
            $this->metadata_storage
        )
            ->getScormPackageMetadata(
                $id
            );
    }


    public function uploadScormPackage(string $id, string $title, string $file) : void
    {
        UploadScormPackageCommand::new(
            $this->filesystem_config,
            $this->metadata_storage
        )
            ->uploadScormPackage(
                $id,
                $title,
                $file
            );
    }
}
