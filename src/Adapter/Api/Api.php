<?php

namespace FluxScormPlayerApi\Adapter\Api;

use FluxScormPlayerApi\Adapter\Config\Config;
use FluxScormPlayerApi\Adapter\Config\EnvConfig;
use FluxScormPlayerApi\Channel\Filesystem\Port\FilesystemService;
use FluxScormPlayerApi\Channel\PlayScormPackage\Port\PlayScormPackageService;

class Api
{

    private Config $config;
    private ?FilesystemService $filesystem = null;
    private ?PlayScormPackageService $play_scorm_package = null;


    public static function new(?Config $config = null) : static
    {
        $api = new static();

        $api->config = $config ?? EnvConfig::new();

        return $api;
    }


    public function deleteScormPackage(string $id) : void
    {
        $this->getFilesystem()
            ->deleteScormPackage(
                $id
            );
    }


    public function getData(string $id, string $user_id) : ?object
    {
        return $this->getPlayScormPackage()
            ->getData(
                $id,
                $user_id
            );
    }


    public function getScormPackageAssetPath(string $id, string $path) : ?string
    {
        return $this->getFilesystem()
            ->getScormPackageAssetPath(
                $id,
                $path
            );
    }


    public function getStaticPath(string $path) : ?string
    {
        return $this->getPlayScormPackage()
            ->getStaticPath(
                $path
            );
    }


    public function playScormPackage(string $id, string $user_id) : ?string
    {
        return $this->getPlayScormPackage()
            ->playScormPackage(
                $id,
                $user_id
            );
    }


    public function storeData(string $id, string $user_id, object $data) : ?object
    {
        return $this->getPlayScormPackage()
            ->storeData(
                $id,
                $user_id,
                $data
            );
    }


    public function uploadScormPackage(string $id, string $title, string $file) : void
    {
        $this->getFilesystem()
            ->uploadScormPackage(
                $id,
                $title,
                $file
            );
    }


    private function getFilesystem() : FilesystemService
    {
        $this->filesystem ??= FilesystemService::new(
            $this->config->getFilesystemConfig(),
            $this->config->getMetadataStorage(),
            $this->config->getDataStorage()
        );

        return $this->filesystem;
    }


    private function getPlayScormPackage() : PlayScormPackageService
    {
        $this->play_scorm_package ??= PlayScormPackageService::new(
            $this->getFilesystem(),
            $this->config->getDataStorage()
        );

        return $this->play_scorm_package;
    }
}
