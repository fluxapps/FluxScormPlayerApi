<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\DeleteScormPackage;

use Fluxlabs\FluxScormPlayerApi\Adapter\Config\FilesystemConfigDto;
use Fluxlabs\FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use Fluxlabs\FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;

class DeleteScormPackageCommandHandler
{

    private DataStorage $data_storage;
    private FilesystemConfigDto $filesystem_config;
    private MetadataStorage $metadata_storage;


    public static function new(FilesystemConfigDto $filesystem_config, MetadataStorage $metadata_storage, DataStorage $data_storage) : static
    {
        $handler = new static();

        $handler->filesystem_config = $filesystem_config;
        $handler->metadata_storage = $metadata_storage;
        $handler->data_storage = $data_storage;

        return $handler;
    }


    public function handle(DeleteScormPackageCommand $command) : void
    {
        if (file_exists($path = $this->filesystem_config->getFolder() . "/" . $command->getId())) {
            exec("rm -rf " . escapeshellarg($path));
        }

        $this->metadata_storage->deleteMetadata(
            $command->getId()
        );

        $this->data_storage->deleteData(
            $command->getId()
        );
    }
}
