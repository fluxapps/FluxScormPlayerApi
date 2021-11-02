<?php

namespace FluxScormPlayerApi;

if (version_compare(PHP_VERSION, ($min_php_version = "8.0"), "<")) {
    die(__NAMESPACE__ . " needs at least PHP " . $min_php_version);
}

foreach (["curl", "json", "mongodb", "simplexml", "swoole", "zip"] as $ext) {
    if (!extension_loaded($ext)) {
        die(__NAMESPACE__ . " needs PHP ext " . $ext);
    }
}

require_once __DIR__ . "/../libs/FluxRestApi/autoload.php";

spl_autoload_register(function (string $class) : void {
    if (str_starts_with($class, "MongoDB\\")) {
        require_once __DIR__ . "/../libs/mongo-php-library/src" . str_replace("\\", "/", substr($class, strlen("MongoDB"))) . ".php";
    }
});
require_once __DIR__ . "/../libs/mongo-php-library/src/functions.php";

spl_autoload_register(function (string $class) : void {
    if (str_starts_with($class, __NAMESPACE__ . "\\")) {
        require_once __DIR__ . str_replace("\\", "/", substr($class, strlen(__NAMESPACE__))) . ".php";
    }
});
