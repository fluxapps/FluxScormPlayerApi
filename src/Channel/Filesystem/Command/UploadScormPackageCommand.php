<?php

namespace FluxScormPlayerApi\Channel\Filesystem\Command;

use Exception;
use FluxScormPlayerApi\Adapter\Config\FilesystemConfigDto;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataDto;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataStorage;
use FluxScormPlayerApi\Adapter\MetadataStorage\MetadataType;
use ZipArchive;

class UploadScormPackageCommand
{

    private function __construct(
        private readonly FilesystemConfigDto $filesystem_config,
        private readonly MetadataStorage $metadata_storage
    ) {

    }


    public static function new(
        FilesystemConfigDto $filesystem_config,
        MetadataStorage $metadata_storage
    ) : static {
        return new static(
            $filesystem_config,
            $metadata_storage
        );
    }


    public function uploadScormPackage(string $id, string $title, string $file) : void
    {
        $path = $this->filesystem_config->folder . "/" . $id;

        $this->extractZipFile(
            $file,
            $path
        );

        $manifest = json_decode(json_encode(simplexml_load_file($path . "/imsmanifest.xml")), true);

        switch ($manifest["metadata"]["schemaversion"]) {
            case "1.2":
                $type = MetadataType::_1_2;
                break;

            case "CAM 1.3":
            case "2004 3rd Edition":
            case "2004 4rd Edition":
                $type = MetadataType::_2004;
                break;

            default:
                throw new Exception("Unknown scorm type " . $manifest["metadata"]["schemaversion"]);
        }

        $this->metadata_storage->storeMetadata(
            $id,
            MetadataDto::new(
                $title,
                $manifest["resources"]["resource"]["@attributes"]["href"],
                $type
            )
        );
    }


    private function extractZipFile(string $file, string $path) : void
    {
        $zip = null;
        try {
            if (file_exists($path)) {
                exec("rm -rf " . escapeshellarg($path));
            }

            $zip = new ZipArchive();

            if (($open = $zip->open($file)) !== true) {
                throw new Exception("Failed to open " . $file . " - Error code: " . $open);
            }

            if (!$zip->extractTo($path)) {
                throw new Exception("Failed to extract " . $file . " to " . $path);
            }
        } finally {
            if ($zip !== null) {
                $zip->close();
            }
        }
    }
}
