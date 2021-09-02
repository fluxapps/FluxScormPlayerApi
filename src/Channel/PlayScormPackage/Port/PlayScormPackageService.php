<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Port;

use Fluxlabs\FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Port\FilesystemService;
use Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\GetDataCommand;
use Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\GetStaticPathCommand;
use Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\PlayScormPackageCommand;
use Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\StoreDataCommand;

class PlayScormPackageService
{

    private DataStorage $data_storage;
    private FilesystemService $filesystem;


    public static function new(FilesystemService $filesystem, DataStorage $data_storage) : static
    {
        $service = new static();

        $service->filesystem = $filesystem;
        $service->data_storage = $data_storage;

        return $service;
    }


    public function getData(string $id, string $user_id) : ?object
    {
        return GetDataCommand::new(
            $this->filesystem,
            $this->data_storage
        )
            ->getData(
                $id,
                $user_id
            );
    }


    public function getStaticPath(string $path) : ?string
    {
        return GetStaticPathCommand::new()
            ->getStaticPath(
                $path
            );
    }


    public function playScormPackage(string $id, string $user_id) : ?string
    {
        return PlayScormPackageCommand::new(
            $this->filesystem
        )
            ->playScormPackage(
                $id,
                $user_id
            );
    }


    public function storeData(string $id, string $user_id, object $data) : ?object
    {
        return StoreDataCommand::new(
            $this->filesystem,
            $this->data_storage
        )
            ->storeData(
                $id,
                $user_id,
                $data
            );
    }
}
