<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\GetScormPackageAssetPath;

class GetScormPackageAssetPathCommand
{

    private string $id;
    private string $path;


    public static function new(string $id, string $path) : static
    {
        $command = new static();

        $command->id = $id;
        $command->path = $path;

        return $command;
    }


    public function getId() : string
    {
        return $this->id;
    }


    public function getPath() : string
    {
        return $this->path;
    }
}
