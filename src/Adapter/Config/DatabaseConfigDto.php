<?php

namespace FluxScormPlayerApi\Adapter\Config;

class DatabaseConfigDto
{

    public readonly string $database;
    public readonly string $host;
    public readonly string $password;
    public readonly int $port;
    public readonly string $user;


    public static function new(string $password, ?string $host = null, ?int $port = null, ?string $user = null, ?string $database = null) : static
    {
        $dto = new static();

        $dto->password = $password;
        $dto->host = $host ?? "scorm-player-db";
        $dto->port = $port ?? 27017;
        $dto->user = $user ?? "scorm-player";
        $dto->database = $database ?? "scorm-player";

        return $dto;
    }
}
