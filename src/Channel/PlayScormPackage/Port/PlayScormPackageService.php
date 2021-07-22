<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Port;

use Fluxlabs\FluxScormPlayerApi\Adapter\DataStorage\DataStorage;
use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Port\FilesystemService;
use Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\GetData\GetDataCommand;
use Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\GetData\GetDataCommandHandler;
use Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\GetStaticPath\GetStaticPathCommand;
use Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\GetStaticPath\GetStaticPathCommandHandler;
use Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\PlayScormPackage\PlayScormPackageCommand;
use Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\PlayScormPackage\PlayScormPackageCommandHandler;
use Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\StoreData\StoreDataCommand;
use Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\StoreData\StoreDataCommandHandler;

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
        return GetDataCommandHandler::new(
            $this->filesystem,
            $this->data_storage
        )
            ->handle(
                GetDataCommand::new(
                    $id,
                    $user_id
                )
            );
    }


    public function getStaticPath(string $path) : ?string
    {
        return GetStaticPathCommandHandler::new()
            ->handle(
                GetStaticPathCommand::new(
                    $path
                )
            );
    }


    public function playScormPackage(string $id, string $user_id) : ?string
    {
        return PlayScormPackageCommandHandler::new(
            $this->filesystem
        )
            ->handle(
                PlayScormPackageCommand::new(
                    $id,
                    $user_id
                )
            );
    }


    public function storeData(string $id, string $user_id, object $data) : ?object
    {
        return StoreDataCommandHandler::new(
            $this->filesystem,
            $this->data_storage
        )
            ->handle(
                StoreDataCommand::new(
                    $id,
                    $user_id,
                    $data
                )
            );
    }
}
