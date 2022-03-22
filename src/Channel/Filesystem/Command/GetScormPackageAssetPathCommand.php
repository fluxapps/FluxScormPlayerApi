<?php

namespace FluxScormPlayerApi\Channel\Filesystem\Command;

use FluxScormPlayerApi\Channel\Filesystem\FilesystemUtils;
use FluxScormPlayerApi\Libs\FluxFileStorageApi\Adapter\Api\FileStorageApi;

class GetScormPackageAssetPathCommand
{

    use FilesystemUtils;

    private function __construct(
        private readonly FileStorageApi $file_storage_api
    ) {

    }


    public static function new(
        FileStorageApi $file_storage_api
    ) : static {
        return new static(
            $file_storage_api
        );
    }


    public function getScormPackageAssetPath(string $id, string $path) : ?string
    {
        $id = $this->normalizeId(
            $id
        );

        return $this->file_storage_api->getFullPath(
            $id . "/" . $path
        );
    }
}
