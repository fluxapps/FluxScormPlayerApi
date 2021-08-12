<?php

namespace Fluxlabs\FluxScormPlayerApi\Adapter\Route;

use Fluxlabs\FluxRestApi\Body\BodyType;
use Fluxlabs\FluxRestApi\Request\RequestDto;
use Fluxlabs\FluxRestApi\Response\ResponseDto;
use Fluxlabs\FluxRestApi\Route\Route;
use Fluxlabs\FluxScormPlayerApi\Adapter\Api\Api;

class UploadRoute implements Route
{

    private Api $api;


    public static function new(Api $api) : static
    {
        $route = new static();

        $route->api = $api;

        return $route;
    }


    public function getBodyType() : ?string
    {
        return BodyType::FORM_DATA;
    }


    public function getMethod() : string
    {
        return "POST";
    }


    public function getRoute() : string
    {
        return "/upload";
    }


    public function handle(RequestDto $request) : ResponseDto
    {
        $this->api->uploadScormPackage(
            $request->getParsedBody()->getData()["id"],
            $request->getParsedBody()->getData()["title"],
            $request->getParsedBody()->getFiles()["file"]["tmp_name"]
        );

        return ResponseDto::new();
    }
}
