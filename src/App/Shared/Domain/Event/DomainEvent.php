<?php

declare(strict_types=1);


namespace App\Shared\Domain\Event;


interface DomainEvent
{
    public function serialize(): array;

    public static function deserialize(array $data): self;
}