<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\GetData;

use Fluxlabs\FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Port\FilesystemService;

class GetDataCommandHandler
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


    public function handle(GetDataCommand $command) : ?object
    {
        $metadata = $this->filesystem->getScormPackageMetadata(
            $command->getId()
        );

        if ($metadata === null) {
            return null;
        }

        return $this->data_storage->getData(
                $command->getId(),
                $command->getUserId()
            ) ?? (object) [];
    }
}
