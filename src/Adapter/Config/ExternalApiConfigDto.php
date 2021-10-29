<?php

namespace FluxScormPlayerApi\Adapter\Config;

class ExternalApiConfigDto
{

    private string $delete_data_url;
    private string $get_data_url;
    private string $store_data_url;


    public static function new(string $get_data_url, string $store_data_url, string $delete_data_url) : static
    {
        $dto = new static();

        $dto->get_data_url = $get_data_url;
        $dto->store_data_url = $store_data_url;
        $dto->delete_data_url = $delete_data_url;

        return $dto;
    }


    public function getDeleteDataUrl() : string
    {
        return $this->delete_data_url;
    }


    public function getGetDataUrl() : string
    {
        return $this->get_data_url;
    }


    public function getStoreDataUrl() : string
    {
        return $this->store_data_url;
    }
}
