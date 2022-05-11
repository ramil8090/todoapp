<?php

declare(strict_types=1);

namespace App\Shared\Application\Query;

final class Item
{
    public string $id;

    public string $type;

    public array $resource;

    public array $relationships = [];

    private function __construct(string $id, string $type, array $payload, array $relations = [])
    {
        $this->id = $id;
        $this->type = $type;
        $this->resource = $payload;
        $this->relationships = $relations;
    }

    public static function fromPayload(string $id, string $type, array $payload, array $relations = []): self
    {
        return new self(
            $id,
            $type,
            $payload,
            $relations
        );
    }
}
