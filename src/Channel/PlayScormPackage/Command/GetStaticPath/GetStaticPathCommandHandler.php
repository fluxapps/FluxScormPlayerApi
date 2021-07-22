<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\GetStaticPath;

class GetStaticPathCommandHandler
{

    public static function new() : static
    {
        $handler = new static();

        return $handler;
    }


    public function handle(GetStaticPathCommand $command) : ?string
    {
        $path = __DIR__ . "/static/" . $command->getPath();

        if (!file_exists($path)) {
            return null;
        }

        return $path;
    }
}
