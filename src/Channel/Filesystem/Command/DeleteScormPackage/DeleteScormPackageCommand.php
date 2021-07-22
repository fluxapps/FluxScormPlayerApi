<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\DeleteScormPackage;

class DeleteScormPackageCommand
{

    private string $id;


    public static function new(string $id) : static
    {
        $command = new static();

        $command->id = $id;

        return $command;
    }


    public function getId() : string
    {
        return $this->id;
    }
}
