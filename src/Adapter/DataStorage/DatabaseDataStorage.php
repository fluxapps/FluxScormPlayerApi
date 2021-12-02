<?php

namespace FluxScormPlayerApi\Adapter\DataStorage;

use MongoDB\Collection;

class DatabaseDataStorage implements DataStorage
{

    private readonly Collection $collection;


    public static function new(Collection $collection) : static
    {
        $data_storage = new static();

        $data_storage->collection = $collection;

        return $data_storage;
    }


    public function deleteData(string $scorm_id) : void
    {
        $this->collection->deleteMany([
            "scorm_id" => $scorm_id
        ]);
    }


    public function getData(string $scorm_id, string $user_id) : ?object
    {
        return $this->collection->findOne([
                "scorm_id" => $scorm_id,
                "user_id"  => $user_id
            ])["data"] ?? (object) [];
    }


    public function storeData(string $scorm_id, string $user_id, object $data) : void
    {
        $this->collection->replaceOne([
            "scorm_id" => $scorm_id,
            "user_id"  => $user_id
        ], [
            "scorm_id" => $scorm_id,
            "user_id"  => $user_id,
            "data"     => $data
        ], ["upsert" => true]);
    }
}
