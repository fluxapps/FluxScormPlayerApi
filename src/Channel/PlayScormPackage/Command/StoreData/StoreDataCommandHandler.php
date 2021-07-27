<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\StoreData;

use Fluxlabs\FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Port\FilesystemService;

class StoreDataCommandHandler
{

    private DataStorage $data_storage;
    private FilesystemService $filesystem;


    public static function new(FilesystemService $filesystem, DataStorage $data_storage) : static
    {
        $handler = new static();

        $handler->filesystem = $filesystem;
        $handler->data_storage = $data_storage;

        return $handler;
    }


    public function handle(StoreDataCommand $command) : ?object
    {
        $metadata = $this->filesystem->getScormPackageMetadata(
            $command->getId()
        );

        if ($metadata === null) {
            return null;
        }

        $this->data_storage->storeData(
            $command->getId(),
            $command->getUserId(),
            $command->getData()
        );

        return $command->getData();
    }
}
