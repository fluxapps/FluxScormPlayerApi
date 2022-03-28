<?php

namespace FluxScormPlayerApi\Adapter\DataStorage;

enum DataStorageConfigType: string
{

    case DATABASE = "database";
    case EXTERNAL_API = "external_api";
}
