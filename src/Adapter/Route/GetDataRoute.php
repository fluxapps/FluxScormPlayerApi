<?php

namespace FluxScormPlayerApi\Adapter\Route;

use FluxRestApi\Body\JsonBodyDto;
use FluxRestApi\Method\Method;
use FluxRestApi\Request\RequestDto;
use FluxRestApi\Response\ResponseDto;
use FluxRestApi\Route\Route;
use FluxRestApi\Status\Status;
use FluxScormPlayerApi\Adapter\Api\Api;

class GetDataRoute implements Route
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
        return "/data/{scorm_id}/{user_id}";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        $data = $this->api->getData(
            $request->getParam(
                "scorm_id"
            ),
            $request->getParam(
                "user_id"
            )
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
                Status::_403
            );
        }
    }
}
