<?php

namespace Fluxlabs\FluxScormPlayerApi\Adapter\Route;

use Fluxlabs\FluxRestApi\Request\RequestDto;
use Fluxlabs\FluxRestApi\Response\ResponseDto;
use Fluxlabs\FluxRestApi\Route\Route;
use Fluxlabs\FluxScormPlayerApi\Adapter\Api\Api;

class DeleteRoute implements Route
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
        return null;
    }


    public function getMethod() : string
    {
        return "DELETE";
    }


    public function getRoute() : string
    {
        return "/delete/{scorm_id}";
    }


    public function handle(RequestDto $request) : ResponseDto
    {
        $this->api->deleteScormPackage(
            $request->getParams()["scorm_id"]
        );

        return ResponseDto::new();
    }
}
