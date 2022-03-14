<?php

namespace FluxScormPlayerApi\Channel\Filesystem\Command;

use FluxScormPlayerApi\Adapter\Config\FilesystemConfigDto;
use FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;

class DeleteScormPackageCommand
{

    private function __construct(
        private readonly FilesystemConfigDto $filesystem_config,
        private readonly MetadataStorage $metadata_storage,
        private readonly DataStorage $data_storage
    ) {

    }


    public static function new(
        FilesystemConfigDto $filesystem_config,
        MetadataStorage $metadata_storage,
        DataStorage $data_storage
    ) : static {
        return new static(
            $filesystem_config,
            $metadata_storage,
            $data_storage
        );
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
