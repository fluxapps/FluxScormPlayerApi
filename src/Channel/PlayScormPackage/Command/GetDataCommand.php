<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command;

use Fluxlabs\FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Port\FilesystemService;

class GetDataCommand
{

    private DataStorage $data_storage;
    private FilesystemService $filesystem;


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
