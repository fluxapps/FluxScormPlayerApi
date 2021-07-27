<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\PlayScormPackage;

use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Port\FilesystemService;

class PlayScormPackageCommandHandler
{

    private FilesystemService $filesystem;


    public static function new(FilesystemService $filesystem) : static
    {
        $handler = new static();

        $handler->filesystem = $filesystem;

        return $handler;
    }


    public function handle(PlayScormPackageCommand $command) : ?string
    {
        $metadata = $this->filesystem->getScormPackageMetadata(
            $command->getId()
        );

        if ($metadata === null) {
            return null;
        }

        $config = [
            "type"         => $metadata->getType(),
            "api_settings" => [
                "autocommit"            => true,
                "autocommitSeconds"     => 30,
                "lmsCommitUrl"          => "data/" . $command->getId() . "/" . $command->getUserId(),
                "dataCommitFormat"      => "json",
                "commitRequestDataType" => "application/json;charset=utf-8",
                "autoProgress"          => false,
                "logLevel"              => 1,
                "mastery_override"      => false,
                "selfReportSessionTime" => false,
                "alwaysSendTotalTime"   => false
            ]
        ];

        $html = file_get_contents(__DIR__ . "/template/index.html");

        $placeholders = [
            "config"     => base64_encode(json_encode($config, JSON_UNESCAPED_SLASHES)),
            "entrypoint" => $metadata->getEntrypoint(),
            "id"         => $command->getId(),
            "title"      => $metadata->getTitle()
        ];

        $html = preg_replace_callback("/{([a-z_]+)}/", fn(array $matches) : string => htmlspecialchars($placeholders[$matches[1]]), $html);

        return $html;
    }
}
