<?php

namespace FluxScormPlayerApi;

require_once __DIR__ . "/../libs/FluxRestApi/autoload.php";

use FluxAutoloadApi\Adapter\Autoload\ComposerAutoload;
use FluxAutoloadApi\Adapter\Autoload\Psr4Autoload;
use FluxAutoloadApi\Adapter\Checker\PhpExtChecker;
use FluxAutoloadApi\Adapter\Checker\PhpVersionChecker;

PhpVersionChecker::new(
    ">=8.0",
    __NAMESPACE__
)
    ->check();
PhpExtChecker::new(
    [
        "curl",
        "json",
        "mongodb",
        "simplexml",
        "swoole",
        "zip"
    ],
    __NAMESPACE__
)
    ->check();

Psr4Autoload::new(
    [
        __NAMESPACE__ => __DIR__
    ]
)
    ->autoload();

ComposerAutoload::new(
    __DIR__ . "/../libs/mongo-php-library"
)
    ->autoload();
