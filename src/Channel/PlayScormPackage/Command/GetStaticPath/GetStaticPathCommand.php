<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\GetStaticPath;

class GetStaticPathCommand
{

    private string $path;


    public static function new(string $path) : static
    {
        $command = new static();

        $command->path = $path;

        return $command;
    }


    public function getPath() : string
    {
        return $this->path;
    }
}
