<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Port;

use Fluxlabs\FluxScormPlayerApi\Adapter\Config\FilesystemConfigDto;
use Fluxlabs\FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use Fluxlabs\FluxScormPlayerApi\Adapter\MetadataStorage\MetadataDto;
use Fluxlabs\FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\DeleteScormPackage\DeleteScormPackageCommand;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\DeleteScormPackage\DeleteScormPackageCommandHandler;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\GetScormPackageAssetPath\GetScormPackageAssetPathCommand;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\GetScormPackageAssetPath\GetScormPackageAssetPathCommandHandler;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\GetScormPackageMetadata\GetScormPackageMetadataCommand;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\GetScormPackageMetadata\GetScormPackageMetadataCommandHandler;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\UploadScormPackage\UploadScormPackageCommand;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\UploadScormPackage\UploadScormPackageCommandHandler;

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
        DeleteScormPackageCommandHandler::new(
            $this->filesystem_config,
            $this->metadata_storage,
            $this->data_storage
        )
            ->handle(
                DeleteScormPackageCommand::new(
                    $id
                )
            );
    }


    public function getScormPackageAssetPath(string $id, string $path) : ?string
    {
        return GetScormPackageAssetPathCommandHandler::new(
            $this->filesystem_config
        )
            ->handle(
                GetScormPackageAssetPathCommand::new(
                    $id,
                    $path
                )
            );
    }


    public function getScormPackageMetadata(string $id) : ?MetadataDto
    {
        return GetScormPackageMetadataCommandHandler::new(
            $this->filesystem_config,
            $this->metadata_storage
        )
            ->handle(
                GetScormPackageMetadataCommand::new(
                    $id
                )
            );
    }


    public function uploadScormPackage(string $id, string $title, string $file) : void
    {
        UploadScormPackageCommandHandler::new(
            $this->filesystem_config,
            $this->metadata_storage
        )
            ->handle(
                UploadScormPackageCommand::new(
                    $id,
                    $title,
                    $file
                )
            );
    }
}
