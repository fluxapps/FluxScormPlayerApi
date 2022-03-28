<?php

namespace FluxScormPlayerApi\Adapter\DataStorage;

use Exception;
use FluxScormPlayerApi\Libs\FluxRestBaseApi\Body\DefaultBodyType;
use FluxScormPlayerApi\Libs\FluxRestBaseApi\Header\DefaultHeader;
use FluxScormPlayerApi\Libs\FluxRestBaseApi\Method\DefaultMethod;
use FluxScormPlayerApi\Libs\FluxRestBaseApi\Method\Method;

class ExternalApiDataStorage implements DataStorage
{

    private function __construct(
        private readonly ExternalApiDataStorageConfigDto $external_api_data_storage_config
    ) {

    }


    public static function new(
        ExternalApiDataStorageConfigDto $external_api_data_storage_config
    ) : static {
        return new static(
            $external_api_data_storage_config
        );
    }


    public function deleteData(string $scorm_id) : void
    {
        $this->request(
            $this->external_api_data_storage_config->delete_data_url,
            $scorm_id,
            null,
            DefaultMethod::DELETE
        );
    }


    public function getData(string $scorm_id, string $user_id) : ?object
    {
        return $this->request(
            $this->external_api_data_storage_config->get_data_url,
            $scorm_id,
            $user_id
        );
    }


    public function storeData(string $scorm_id, string $user_id, object $data) : void
    {
        $this->request(
            $this->external_api_data_storage_config->store_data_url,
            $scorm_id,
            $user_id,
            DefaultMethod::POST,
            $data
        );
    }


    private function request(string $url, string $scorm_id, ?string $user_id = null, ?Method $method = null, ?object $data = null) : ?object
    {
        $method = $method ?? DefaultMethod::GET;

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
                DefaultHeader::USER_AGENT->value => "FluxScormPlayerApi"
            ];

            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method->value);

            if ($data !== null) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_SLASHES));
                $headers[DefaultHeader::CONTENT_TYPE->value] = DefaultBodyType::JSON->value;
            }

            $return = $method === DefaultMethod::GET;
            if ($return) {
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $headers[DefaultHeader::ACCEPT->value] = DefaultBodyType::JSON->value;
            }

            curl_setopt($curl, CURLOPT_HTTPHEADER, array_map(fn(string $key, string $value) : string => $key . ": " . $value, array_keys($headers), $headers));

            curl_setopt($curl, CURLOPT_FAILONERROR, true);

            $data = curl_exec($curl);

            if (curl_errno($curl) !== 0) {
                throw new Exception(curl_error($curl));
            }

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
