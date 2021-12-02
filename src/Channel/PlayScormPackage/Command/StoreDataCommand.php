<?php

namespace FluxScormPlayerApi\Channel\PlayScormPackage\Command;

use FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerApi\Channel\Filesystem\Port\FilesystemService;

class StoreDataCommand
{

    private readonly DataStorage $data_storage;
    private readonly FilesystemService $filesystem;


    public static function new(FilesystemService $filesystem, DataStorage $data_storage) : static
    {
        $command = new static();

        $command->filesystem = $filesystem;
        $command->data_storage = $data_storage;

        return $command;
    }


    public function storeData(string $id, string $user_id, object $data) : ?object
    {
        $metadata = $this->filesystem->getScormPackageMetadata(
            $id
        );

        if ($metadata === null) {
            return null;
        }

        $this->data_storage->storeData(
            $id,
            $user_id,
            $data
        );

        return $data;
    }
}
