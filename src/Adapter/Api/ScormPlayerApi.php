<?php

namespace FluxScormPlayerApi\Adapter\Api;

use FluxFileStorageApi\Adapter\Api\FileStorageApi;
use FluxFileStorageApi\Adapter\Api\FileStorageApiConfigDto;
use FluxFileStorageApi\Adapter\Storage\StorageConfigDto;
use FluxScormPlayerApi\Service\Filesystem\Port\FilesystemService;
use FluxScormPlayerApi\Service\PlayScormPackage\Port\PlayScormPackageService;

class ScormPlayerApi
{

    private function __construct(
        private readonly ScormPlayerApiConfigDto $scorm_player_api_config
    ) {

    }


    public static function new(
        ?ScormPlayerApiConfigDto $scorm_player_api_config = null
    ) : static {
        return new static(
            $scorm_player_api_config ?? ScormPlayerApiConfigDto::newFromEnv()
        );
    }


    public function deleteScormPackage(string $id) : void
    {
        $this->getFilesystemService()
            ->deleteScormPackage(
                $id
            );
    }


    public function getData(string $id, string $user_id) : ?object
    {
        return $this->getPlayScormPackageService()
            ->getData(
                $id,
                $user_id
            );
    }


    public function getScormPackageAssetPath(string $id, string $path) : ?string
    {
        return $this->getFilesystemService()
            ->getScormPackageAssetPath(
                $id,
                $path
            );
    }


    public function getStaticPath(string $path) : ?string
    {
        return $this->getPlayScormPackageService()
            ->getStaticPath(
                $path
            );
    }


    public function playScormPackage(string $id, string $user_id) : ?string
    {
        return $this->getPlayScormPackageService()
            ->playScormPackage(
                $id,
                $user_id
            );
    }


    public function storeData(string $id, string $user_id, object $data) : ?object
    {
        return $this->getPlayScormPackageService()
            ->storeData(
                $id,
                $user_id,
                $data
            );
    }


    public function uploadScormPackage(string $id, string $title, string $file) : void
    {
        $this->getFilesystemService()
            ->uploadScormPackage(
                $id,
                $title,
                $file
            );
    }


    private function getFileStorageApi() : FileStorageApi
    {
        return FileStorageApi::new(
            FileStorageApiConfigDto::new(
                StorageConfigDto::new(
                    $this->scorm_player_api_config->filesystem_config->folder
                )
            )
        );
    }


    private function getFilesystemService() : FilesystemService
    {
        return FilesystemService::new(
            $this->getFileStorageApi(),
            $this->scorm_player_api_config->metadata_storage,
            $this->scorm_player_api_config->data_storage
        );
    }


    private function getPlayScormPackageService() : PlayScormPackageService
    {
        return PlayScormPackageService::new(
            $this->getFilesystemService(),
            $this->scorm_player_api_config->data_storage
        );
    }
}
