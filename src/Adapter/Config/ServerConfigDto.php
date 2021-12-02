<?php

namespace FluxScormPlayerApi\Adapter\Config;

class ServerConfigDto
{

    private readonly ?string $https_cert;
    private readonly ?string $https_key;
    private readonly string $listen;
    private readonly int $max_upload_size;
    private readonly int $port;


    public static function new(?string $https_cert = null, ?string $https_key = null, ?string $listen = null, ?int $port = null, ?int $max_upload_size = null) : static
    {
        $dto = new static();

        $dto->https_cert = $https_cert;
        $dto->https_key = $https_key;
        $dto->listen = $listen ?? "0.0.0.0";
        $dto->port = $port ?? 9501;
        $dto->max_upload_size = $max_upload_size ?? 104857600;

        return $dto;
    }


    public function getHttpsCert() : ?string
    {
        return $this->https_cert;
    }


    public function getHttpsKey() : ?string
    {
        return $this->https_key;
    }


    public function getListen() : string
    {
        return $this->listen;
    }


    public function getMaxUploadSize() : int
    {
        return $this->max_upload_size;
    }


    public function getPort() : int
    {
        return $this->port;
    }
}
