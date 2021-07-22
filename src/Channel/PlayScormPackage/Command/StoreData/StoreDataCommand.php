<?php

namespace Fluxlabs\FluxScormPlayerApi\Channel\PlayScormPackage\Command\StoreData;

class StoreDataCommand
{

    private object $data;
    private string $id;
    private string $user_id;


    public static function new(string $id, string $user_id, object $data) : static
    {
        $command = new static();

        $command->id = $id;
        $command->user_id = $user_id;
        $command->data = $data;

        return $command;
    }


    public function getData() : object
    {
        return $this->data;
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
