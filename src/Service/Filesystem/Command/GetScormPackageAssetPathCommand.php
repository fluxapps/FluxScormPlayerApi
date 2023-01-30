<?php

namespace FluxScormPlayerApi\Service\Filesystem\Command;

use FluxFileStorageApi\Adapter\Api\FileStorageApi;
use FluxScormPlayerApi\Service\Filesystem\FilesystemUtils;

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
