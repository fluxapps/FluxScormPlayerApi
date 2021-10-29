<?php

namespace FluxScormPlayerApi\Adapter\Config;

class FilesystemConfigDto
{

    private string $folder;


    public static function new(?string $folder = null) : static
    {
        $dto = new static();

        $dto->folder = $folder ?? "/scorm";

        return $dto;
    }


    public function getFolder() : string
    {
        return $this->folder;
    }
}
