<?php

namespace FluxScormPlayerApi;

require_once __DIR__ . "/../libs/flux-autoload-api/autoload.php";
require_once __DIR__ . "/../libs/flux-file-storage-api/autoload.php";
require_once __DIR__ . "/../libs/flux-rest-api/autoload.php";

use FluxScormPlayerApi\Libs\FluxAutoloadApi\Adapter\Autoload\ComposerAutoload;
use FluxScormPlayerApi\Libs\FluxAutoloadApi\Adapter\Autoload\Psr4Autoload;
use FluxScormPlayerApi\Libs\FluxAutoloadApi\Adapter\Checker\PhpExtChecker;
use FluxScormPlayerApi\Libs\FluxAutoloadApi\Adapter\Checker\PhpVersionChecker;

PhpVersionChecker::new(
    ">=8.1"
)
    ->checkAndDie(
        __NAMESPACE__
    );
PhpExtChecker::new(
    [
        "json",
        "mongodb",
        "simplexml"
    ]
)
    ->checkAndDie(
        __NAMESPACE__
    );

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
