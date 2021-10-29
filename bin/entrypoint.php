#!/usr/bin/env php
<?php

require_once __DIR__ . "/../src/init.php";

use FluxScormPlayerApi\Adapter\Server\Server;

Server::new()
    ->init();
