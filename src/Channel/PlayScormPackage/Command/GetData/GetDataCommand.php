<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\GetData;

class GetDataCommand
{

    private string $id;
    private string $user_id;


    public static function new(string $id, string $user_id) : static
    {
        $command = new static();

        $command->id = $id;
        $command->user_id = $user_id;

        return $command;
    }


    public function getId() : string
    {
        return $this->id;
    }


    public function getUserId() : string
    {
        return $this->user_id;
    }
}
