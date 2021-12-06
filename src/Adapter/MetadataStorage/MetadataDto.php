<?php

namespace FluxScormPlayerApi\Adapter\MetadataStorage;

class MetadataDto
{

    public readonly string $entrypoint;
    public readonly string $title;
    public readonly MetadataType $type;


    public static function new(string $title, string $entrypoint, MetadataType $type) : static
    {
        $dto = new static();

        $dto->title = $title;
        $dto->entrypoint = $entrypoint;
        $dto->type = $type;

        return $dto;
    }
}
