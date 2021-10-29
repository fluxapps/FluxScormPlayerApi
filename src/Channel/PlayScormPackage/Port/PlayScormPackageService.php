<?php

namespace FluxScormPlayerApi\Channel\PlayScormPackage\Port;

use FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use FluxScormPlayerApi\Channel\Filesystem\Port\FilesystemService;
use FluxScormPlayerApi\Channel\PlayScormPackage\Command\GetDataCommand;
use FluxScormPlayerApi\Channel\PlayScormPackage\Command\GetStaticPathCommand;
use FluxScormPlayerApi\Channel\PlayScormPackage\Command\PlayScormPackageCommand;
use FluxScormPlayerApi\Channel\PlayScormPackage\Command\StoreDataCommand;

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
