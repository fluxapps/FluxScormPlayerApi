<?php

namespace FluxScormPlayerApi\Adapter\Config;

class DataStorageConfigDto
{

    const TYPE_DATABASE = "database";
    const TYPE_EXTERNAL_API = "external_api";
    private string $type;


    public static function new(?string $type = null) : static
    {
        $dto = new static();

        $dto->type = $type ?? static::TYPE_DATABASE;

        return $dto;
    }


    public function getType() : string
    {
        return $this->type;
    }
}
