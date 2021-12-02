<?php

namespace FluxScormPlayerApi\Adapter\Route;

use FluxRestApi\Body\FormDataBodyDto;
use FluxRestApi\Body\TextBodyDto;
use FluxRestApi\Request\RequestDto;
use FluxRestApi\Response\ResponseDto;
use FluxRestApi\Route\Route;
use FluxRestBaseApi\Body\DefaultBodyType;
use FluxRestBaseApi\Method\DefaultMethod;
use FluxRestBaseApi\Method\Method;
use FluxRestBaseApi\Status\DefaultStatus;
use FluxScormPlayerApi\Adapter\Api\Api;

class UploadRoute implements Route
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
        return [
            DefaultBodyType::FORM_DATA
        ];
    }


    public function getDocuRequestQueryParams() : ?array
    {
        return null;
    }


    public function getMethod() : Method
    {
        return DefaultMethod::POST;
    }


    public function getRoute() : string
    {
        return "/upload";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        if (!($request->getParsedBody() instanceof FormDataBodyDto)) {
            return ResponseDto::new(
                TextBodyDto::new(
                    "No form data body"
                ),
                DefaultStatus::_400
            );
        }

        $this->api->uploadScormPackage(
            $request->getParsedBody()->getData()["id"],
            $request->getParsedBody()->getData()["title"],
            $request->getParsedBody()->getFiles()["file"]["tmp_name"]
        );

        return null;
    }
}
