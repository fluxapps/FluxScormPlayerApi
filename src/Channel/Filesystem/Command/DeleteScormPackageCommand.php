<?php

namespace FluxScormPlayerApi\Channel\Filesystem\Command;

use FluxScormPlayerApi\Adapter\Config\FilesystemConfigDto;
use FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;

class DeleteScormPackageCommand
{

    private readonly DataStorage $data_storage;
    private readonly FilesystemConfigDto $filesystem_config;
    private readonly MetadataStorage $metadata_storage;


    public static function new(FilesystemConfigDto $filesystem_config, MetadataStorage $metadata_storage, DataStorage $data_storage) : static
    {
        $command = new static();

        $command->filesystem_config = $filesystem_config;
        $command->metadata_storage = $metadata_storage;
        $command->data_storage = $data_storage;

        return $command;
    }


    public function deleteScormPackage(string $id) : void
    {
        if (file_exists($path = $this->filesystem_config->folder . "/" . $id)) {
            exec("rm -rf " . escapeshellarg($path));
        }

        $this->metadata_storage->deleteMetadata(
            $id
        );

        $this->data_storage->deleteData(
            $id
        );
    }
}
