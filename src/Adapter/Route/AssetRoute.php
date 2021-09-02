<?php

namespace Fluxlabs\FluxScormPlayerApi\Adapter\Route;

use Fluxlabs\FluxRestApi\Method\Method;
use Fluxlabs\FluxRestApi\Request\RequestDto;
use Fluxlabs\FluxRestApi\Response\ResponseDto;
use Fluxlabs\FluxRestApi\Route\Route;
use Fluxlabs\FluxRestApi\Status\Status;
use Fluxlabs\FluxScormPlayerApi\Adapter\Api\Api;

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
            $request->getParam("scorm_id"),
            $request->getParam("path")
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
