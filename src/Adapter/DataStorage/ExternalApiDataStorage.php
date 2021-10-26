<?php

namespace Fluxlabs\FluxScormPlayerApi\Adapter\DataStorage;

use Fluxlabs\FluxRestApi\Header\Header;
use Fluxlabs\FluxRestApi\Method\Method;
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
            Method::DELETE
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


    public function storeData(string $scorm_id, string $user_id, object $data) : void
    {
        $this->request(
            $this->external_api_config->getStoreDataUrl(),
            $scorm_id,
            $user_id,
            Method::POST,
            $data
        );
    }


    private function request(string $url, string $scorm_id, ?string $user_id = null, ?string $method = Method::GET, ?object $data = null) : ?object
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
                Header::USER_AGENT => "FluxScormPlayerApi"
            ];

            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

            if ($data !== null) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_SLASHES));
                $headers[Header::CONTENT_TYPE] = "application/json";
            }

            $return = $method === Method::GET;
            if ($return) {
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $headers[Header::ACCEPT] = "application/json";
            }

            curl_setopt($curl, CURLOPT_HTTPHEADER, array_map(fn(string $key, string $value) : string => $key . ": " . $value, array_keys($headers), $headers));

            $data = curl_exec($curl);

            if (!$return || empty($data) || empty($data = json_decode($data))) {
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
