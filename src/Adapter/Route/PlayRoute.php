<?php

namespace FluxScormPlayerApi\Adapter\Route;

use FluxRestApi\Body\HtmlBodyDto;
use FluxRestApi\Request\RequestDto;
use FluxRestApi\Response\ResponseDto;
use FluxRestApi\Route\Route;
use FluxRestBaseApi\Method\DefaultMethod;
use FluxRestBaseApi\Method\Method;
use FluxRestBaseApi\Status\DefaultStatus;
use FluxScormPlayerApi\Adapter\Api\Api;

class PlayRoute implements Route
{

    private readonly Api $api;


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


    public function getMethod() : Method
    {
        return DefaultMethod::GET;
    }


    public function getRoute() : string
    {
        return "/play/{scorm_id}/{user_id}";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        $html = $this->api->playScormPackage(
            $request->getParam(
                "scorm_id"
            ),
            $request->getParam(
                "user_id"
            )
        );

        if ($html !== null) {
            return ResponseDto::new(
                HtmlBodyDto::new(
                    $html
                )
            );
        } else {
            return ResponseDto::new(
                null,
                DefaultStatus::_403
            );
        }
    }
}
