<?php

namespace Fluxlabs\FluxScormPlayerApi\Adapter\Server;

use Fluxlabs\FluxScormPlayerApi\Adapter\Api\Api;
use Fluxlabs\FluxScormPlayerApi\Adapter\Config\Config;
use Fluxlabs\FluxScormPlayerApi\Adapter\Config\EnvConfig;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server as SwooleServer;

class Server
{

    private Api $api;
    private Config $config;


    public static function new(?Config $config = null, ?Api $api = null) : static
    {
        $server = new static();

        $server->config = $config ?? EnvConfig::new();
        $server->api = $api ?? Api::new(
                $server->config
            );

        return $server;
    }


    public function init() : void
    {
        $options = [
            "package_max_length" => $this->config->getServerConfig()->getMaxUploadSize()
        ];
        $sock_type = SWOOLE_TCP;

        if ($this->config->getServerConfig()->getHttpsCert() !== null) {
            $options += [
                "ssl_cert_file" => $this->config->getServerConfig()->getHttpsCert(),
                "ssl_key_file"  => $this->config->getServerConfig()->getHttpsKey()
            ];
            $sock_type += SWOOLE_SSL;
        }

        $server = new SwooleServer($this->config->getServerConfig()->getListen(), $this->config->getServerConfig()->getPort(), SWOOLE_PROCESS, $sock_type);

        $server->set($options);

        $server->on("request", function (Request $request, Response $response) : void {
            $this->request($request, $response);
        });

        $server->start();
    }


    private function assetRequest(Request $request, Response $response) : void
    {
        $path = $this->api->getScormPackageAssetPath(
            explode("/", $request->server["request_uri"])[2],
            implode("/", array_slice(explode("/", $request->server["request_uri"]), 3))
        );

        if ($path !== null) {
            $response->sendfile($path);
        } else {
            $response->status(404);
            $response->end();
        }
    }


    private function dataRequest(Request $request, Response $response) : void
    {
        if ($request->getMethod() === "POST") {
            $data = $this->api->storeData(
                explode("/", $request->server["request_uri"])[2],
                explode("/", $request->server["request_uri"])[3],
                json_decode($request->getContent())
            );
        } else {
            $data = $this->api->getData(
                explode("/", $request->server["request_uri"])[2],
                explode("/", $request->server["request_uri"])[3]
            );
        }

        if ($data !== null) {
            $response->header("Content-Type", "application/json;charset=utf-8");
            $response->write(json_encode($data, JSON_UNESCAPED_SLASHES));
        } else {
            $response->status(403);
        }
        $response->end();
    }


    private function deleteRequest(Request $request, Response $response) : void
    {
        $this->api->deleteScormPackage(
            explode("/", $request->server["request_uri"])[2]
        );

        $response->end();
    }


    private function playRequest(Request $request, Response $response) : void
    {
        $html = $this->api->playScormPackage(
            explode("/", $request->server["request_uri"])[2],
            explode("/", $request->server["request_uri"])[3]
        );

        if ($html !== null) {
            $response->write($html);
        } else {
            $response->status(403);
        }
        $response->end();
    }


    private function request(Request $request, Response $response) : void
    {
        switch (true) {
            case $request->server["request_uri"] === "/upload" && $request->getMethod() === "POST" && str_contains($request->header["content-type"], "multipart/form-data"):
                $this->uploadRequest($request, $response);
                break;

            case str_starts_with($request->server["request_uri"], "/delete/") && substr_count($request->server["request_uri"], "/") === 2 && $request->getMethod() === "DELETE":
                $this->deleteRequest($request, $response);
                break;

            case str_starts_with($request->server["request_uri"], "/play/") && substr_count($request->server["request_uri"], "/") === 3 && $request->getMethod() === "GET":
                $this->playRequest($request, $response);
                break;

            case str_starts_with($request->server["request_uri"], "/js/") && substr_count($request->server["request_uri"], "/") >= 2 && $request->getMethod() === "GET":
            case str_starts_with($request->server["request_uri"], "/css/") && substr_count($request->server["request_uri"], "/") >= 2 && $request->getMethod() === "GET":
            case $request->server["request_uri"] === "/favicon.ico" && $request->getMethod() === "GET":
                $this->staticRequest($request, $response);
                break;

            case str_starts_with($request->server["request_uri"], "/asset/") && substr_count($request->server["request_uri"], "/") >= 3 && $request->getMethod() === "GET":
                $this->assetRequest($request, $response);
                break;

            case str_starts_with($request->server["request_uri"], "/data/") && substr_count($request->server["request_uri"], "/") === 3 && $request->getMethod() === "GET":
            case str_starts_with($request->server["request_uri"], "/data/") && substr_count($request->server["request_uri"], "/") === 3 && $request->getMethod() === "POST"
                && str_contains($request->header["content-type"], "application/json"):
                $this->dataRequest($request, $response);
                break;

            default:
                $response->status(403);
                $response->end();
                break;
        }
    }


    private function staticRequest(Request $request, Response $response) : void
    {
        $path = $this->api->getStaticPath(
            substr($request->server["request_uri"], 1)
        );

        if ($path !== null) {
            $response->sendfile($path);
        } else {
            $response->status(404);
            $response->end();
        }
    }


    private function uploadRequest(Request $request, Response $response) : void
    {
        $this->api->uploadScormPackage(
            $request->post["id"],
            $request->post["title"],
            $request->files["file"]["tmp_name"]
        );

        $response->end();
    }
}
