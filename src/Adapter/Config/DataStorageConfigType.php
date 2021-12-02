<?php

namespace FluxScormPlayerApi\Adapter\Config;

enum DataStorageConfigType: string
{

    case DATABASE = "database";
    case EXTERNAL_API = "external_api";
}
