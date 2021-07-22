<?php

namespace Fluxlabs\FluxScormPlayerApi\Adapter\DataStorage;

use Fluxlabs\FluxScormPlayerApi\Adapter\Config\ExternalApiConfigDto;

class ExternalApiDataStorage implements DataStorage
{

    private ExternalApiConfigDto $external_api_config;


    public static function new(ExternalApiConfigDto $external_api_config) : static
    {
        $data_storage = new static();

        $data_storage->external_api_config = $external_api_config;

        return $data_storage;
    }


    public function deleteData(string $scorm_id) : void
    {
        $this->request(
            $this->external_api_config->getDeleteDataUrl(),
            $scorm_id,
            null,
            "DELETE"
        );
    }


    public function getData(string $scorm_id, string $user_id) : ?object
    {
        return $this->request(
            $this->external_api_config->getGetDataUrl(),
            $scorm_id,
            $user_id
        );
    }


    public function storeData(string $scorm_id, string $user_id, object $data) : ?object
    {
        return $this->request(
            $this->external_api_config->getStoreDataUrl(),
            $scorm_id,
            $user_id,
            "POST",
            $data
        );
    }


    private function request(string $url, string $scorm_id, ?string $user_id = null, ?string $method = "GET", ?object $data = null) : ?object
    {
        $curl = null;
        try {
            $placeholders = [
                "scorm_id" => $scorm_id
            ];
            if ($user_id !== null) {
                $placeholders["user_id"] = $user_id;
            }

            $url = preg_replace_callback("/{([a-z_]+)}/", fn(array $matches) : string => $placeholders[$matches[1]] ?? "", $url);

            $curl = curl_init($url);

            $headers = [
                "User-Agent" => "FluxScormPlayerApi"
            ];

            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

            if ($data !== null) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                $headers["Content-Type"] = "application/json;charset=utf-8";
            }

            $return = $method !== "DELETE";
            if ($return) {
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $headers["Accept"] = "application/json;charset=utf-8";
            }

            curl_setopt($curl, CURLOPT_HTTPHEADER, array_map(fn(string $key, string $value) : string => $key . ": " . $value, array_keys($headers), $headers));

            $data = curl_exec($curl);

            if (!$return || empty($data) || empty($data = json_decode($data, true))) {
                $data = null;
            }

            return $data;
        } finally {
            if ($curl !== null) {
                curl_close($curl);
            }
        }
    }
}
