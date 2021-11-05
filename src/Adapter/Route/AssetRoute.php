<?php

namespace FluxScormPlayerApi\Adapter\Route;

use FluxRestApi\Request\RequestDto;
use FluxRestApi\Response\ResponseDto;
use FluxRestApi\Route\Route;
use FluxRestBaseApi\Method\Method;
use FluxRestBaseApi\Status\Status;
use FluxScormPlayerApi\Adapter\Api\Api;

class AssetRoute implements Route
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
        return Method::GET;
    }


    public function getRoute() : string
    {
        return "/asset/{scorm_id}{path.}";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        $path = $this->api->getScormPackageAssetPath(
            $request->getParam(
                "scorm_id"
            ),
            $request->getParam(
                "path"
            )
        );

        if ($path !== null) {
            return ResponseDto::new(
                null,
                null,
                null,
                null,
                $path
            );
        } else {
            return ResponseDto::new(
                null,
                Status::_404
            );
        }
    }
}
