<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Command\GetScormPackageAssetPath;

use Fluxlabs\FluxScormPlayerApi\Adapter\Config\FilesystemConfigDto;

class GetScormPackageAssetPathCommandHandler
{

    private FilesystemConfigDto $filesystem_config;


    public static function new(FilesystemConfigDto $filesystem_config) : static
    {
        $handler = new static();

        $handler->filesystem_config = $filesystem_config;

        return $handler;
    }


    public function handle(GetScormPackageAssetPathCommand $command) : ?string
    {
        $path = $this->filesystem_config->getFolder() . "/" . $command->getId() . "/" . $command->getPath();

        if (!file_exists($path)) {
            return null;
        }

        return $path;
    }
}
