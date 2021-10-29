<?php

namespace FluxScormPlayerApi\Adapter\MetadataStorage;

class MetadataDto
{

    const TYPE_1_2 = "1_2";
    const TYPE_2004 = "2004";
    const TYPE_AAIC = "aaic";
    private string $entrypoint;
    private string $title;
    private string $type;


    public static function new(string $title, string $entrypoint, string $type) : static
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


    public function getType() : string
    {
        return $this->type;
    }
}
