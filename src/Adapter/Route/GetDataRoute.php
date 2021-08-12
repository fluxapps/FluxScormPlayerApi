<?php

namespace Fluxlabs\FluxScormPlayerApi\Adapter\Route;

use Fluxlabs\FluxRestApi\Body\JsonBodyDto;
use Fluxlabs\FluxRestApi\Request\RequestDto;
use Fluxlabs\FluxRestApi\Response\ResponseDto;
use Fluxlabs\FluxRestApi\Route\Route;
use Fluxlabs\FluxScormPlayerApi\Adapter\Api\Api;

class GetDataRoute implements Route
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
        return "GET";
    }


    public function getRoute() : string
    {
        return "/data/{scorm_id}/{user_id}";
    }


    public function handle(RequestDto $request) : ResponseDto
    {
        $data = $this->api->getData(
            $request->getParams()["scorm_id"],
            $request->getParams()["user_id"]
        );

        if ($data !== null) {
            return ResponseDto::new(
                JsonBodyDto::new(
                    $data
                )
            );
        } else {
            return ResponseDto::new(
                null,
                403
            );
        }
    }
}
