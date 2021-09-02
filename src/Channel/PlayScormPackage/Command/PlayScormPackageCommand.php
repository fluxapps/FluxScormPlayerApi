<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command;

use Fluxlabs\FluxScormPlayerApi\Channel\Filesystem\Port\FilesystemService;

class PlayScormPackageCommand
{

    private FilesystemService $filesystem;


    public static function new(FilesystemService $filesystem) : static
    {
        $command = new static();

        $command->filesystem = $filesystem;

        return $command;
    }


    public function playScormPackage(string $id, string $user_id) : ?string
    {
        $metadata = $this->filesystem->getScormPackageMetadata(
            $id
        );

        if ($metadata === null) {
            return null;
        }

        $config = [
            "type"         => $metadata->getType(),
            "api_settings" => [
                "autocommit"            => true,
                "autocommitSeconds"     => 30,
                "lmsCommitUrl"          => "data/" . $id . "/" . $user_id,
                "dataCommitFormat"      => "json",
                "commitRequestDataType" => "application/json",
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
            "id"         => $id,
            "title"      => $metadata->getTitle()
        ];

        $html = preg_replace_callback("/{([a-z_]+)}/", fn(array $matches) : string => htmlspecialchars($placeholders[$matches[1]]), $html);

        return $html;
    }
}
