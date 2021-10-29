<?php

namespace FluxScormPlayerApi\Channel\PlayScormPackage\Command;

class GetStaticPathCommand
{

    public static function new() : static
    {
        $command = new static();

        return $command;
    }


    public function getStaticPath(string $path) : ?string
    {
        $path = __DIR__ . "/static/" . $path;

        if (!file_exists($path)) {
            return null;
        }

        return $path;
    }
}
