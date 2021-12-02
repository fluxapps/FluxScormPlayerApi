<?php

namespace FluxScormPlayerApi\Adapter\MetadataStorage;

class MetadataDto
{

    private readonly string $entrypoint;
    private readonly string $title;
    private readonly MetadataType $type;


    public static function new(string $title, string $entrypoint, MetadataType $type) : static
    {
        $dto = new static();

        $dto->title = $title;
        $dto->entrypoint = $entrypoint;
        $dto->type = $type;

        return $dto;
    }


    public function getEntrypoint() : string
    {
        return $this->entrypoint;
    }


    public function getTitle() : string
    {
        return $this->title;
    }


    public function getType() : MetadataType
    {
        return $this->type;
    }
}
