<?php

namespace FluxScormPlayerApi\Adapter\Config;

class DataStorageConfigDto
{

    public readonly DataStorageConfigType $type;


    public static function new(?DataStorageConfigType $type = null) : static
    {
        $dto = new static();

        $dto->type = $type ?? DataStorageConfigType::DATABASE;

        return $dto;
    }
}
