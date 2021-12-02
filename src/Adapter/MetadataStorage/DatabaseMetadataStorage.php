<?php

namespace FluxScormPlayerApi\Adapter\MetadataStorage;

use MongoDB\Collection;

class DatabaseMetadataStorage implements MetadataStorage
{

    private readonly Collection $collection;


    public static function new(Collection $collection) : static
    {
        $metadata_storage = new static();

        $metadata_storage->collection = $collection;

        return $metadata_storage;
    }


    public function deleteMetadata(string $scorm_id) : void
    {
        $this->collection->deleteMany([
            "scorm_id" => $scorm_id
        ]);
    }


    public function getMetadata(string $scorm_id) : ?MetadataDto
    {
        $document = $this->collection->findOne([
            "scorm_id" => $scorm_id
        ]);

        if ($document === null) {
            return null;
        }

        return MetadataDto::new(
            $document["title"],
            $document["entrypoint"],
            MetadataType::from($document["type"])
        );
    }


    public function storeMetadata(string $scorm_id, MetadataDto $metadata) : void
    {
        $this->collection->replaceOne([
            "scorm_id" => $scorm_id
        ], [
            "scorm_id"   => $scorm_id,
            "title"      => $metadata->getTitle(),
            "entrypoint" => $metadata->getEntrypoint(),
            "type"       => $metadata->getType()->value
        ], ["upsert" => true]);
    }
}
