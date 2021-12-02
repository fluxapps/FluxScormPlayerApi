<?php

namespace FluxScormPlayerApi\Channel\PlayScormPackage\Command;

use FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerApi\Channel\Filesystem\Port\FilesystemService;

class GetDataCommand
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


    public function getData(string $id, string $user_id) : ?object
    {
        $metadata = $this->filesystem->getScormPackageMetadata(
            $id
        );

        if ($metadata === null) {
            return null;
        }

        return $this->data_storage->getData(
                $id,
                $user_id
            ) ?? (object) [];
    }
}
