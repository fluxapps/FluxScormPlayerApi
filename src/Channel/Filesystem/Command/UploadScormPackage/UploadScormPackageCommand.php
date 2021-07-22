<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\UploadScormPackage;

class UploadScormPackageCommand
{

    private string $file;
    private string $id;
    private string $title;


    public static function new(string $id, string $title, string $file) : static
    {
        $command = new static();

        $command->id = $id;
        $command->title = $title;
        $command->file = $file;

        return $command;
    }


    public function getFile() : string
    {
        return $this->file;
    }


    public function getId() : string
    {
        return $this->id;
    }


    public function getTitle() : string
    {
        return $this->title;
    }
}
