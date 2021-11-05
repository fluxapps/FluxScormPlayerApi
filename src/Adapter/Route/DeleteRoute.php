<?php

namespace FluxScormPlayerApi\Adapter\Route;

use FluxRestApi\Request\RequestDto;
use FluxRestApi\Response\ResponseDto;
use FluxRestApi\Route\Route;
use FluxRestBaseApi\Method\Method;
use FluxScormPlayerApi\Adapter\Api\Api;

class DeleteRoute implements Route
{

    private Api $api;


    public static function new(Api $api) : static
    {
        $route = new static();

        $route->api = $api;

        return $route;
    }


    public function getDocuRequestBodyTypes() : ?array
    {
        return null;
    }


    public function getDocuRequestQueryParams() : ?array
    {
        return null;
    }


    public function getMethod() : string
    {
        return Method::DELETE;
    }


    public function getRoute() : string
    {
        return "/delete/{scorm_id}";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        $this->api->deleteScormPackage(
            $request->getParam(
                "scorm_id"
            )
        );

        return null;
    }
}
