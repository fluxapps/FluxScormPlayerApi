<?php

namespace FluxScormPlayerApi\Channel\Filesystem\Command;

use FluxScormPlayerApi\Adapter\Config\FilesystemConfigDto;

class GetScormPackageAssetPathCommand
{

    private function __construct(
        private readonly FilesystemConfigDto $filesystem_config
    ) {

    }


    public static function new(
        FilesystemConfigDto $filesystem_config
    ) : static {
        return new static(
            $filesystem_config
        );
    }


    public function getScormPackageAssetPath(string $id, string $path) : ?string
    {
        $path = $this->filesystem_config->folder . "/" . $id . "/" . $path;

        if (!file_exists($path)) {
            return null;
        }

        return $path;
    }
}
