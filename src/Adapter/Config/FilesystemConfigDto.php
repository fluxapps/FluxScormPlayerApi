<?php

namespace FluxScormPlayerApi\Adapter\Config;

class FilesystemConfigDto
{

    public readonly string $folder;


    public static function new(?string $folder = null) : static
    {
        $dto = new static();

        $dto->folder = $folder ?? "/scorm";

        return $dto;
    }
}
