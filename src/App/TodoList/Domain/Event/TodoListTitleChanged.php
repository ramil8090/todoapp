<?php

declare(strict_types=1);


namespace App\TodoList\Domain\Event;


use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\ValueObject\DateTime;
use Assert\Assertion;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class TodoListTitleChanged implements DomainEvent
{
    public UuidInterface $uuid;
    public DateTime $updatedAt;

    /**
     * @param UuidInterface $uuid
     * @param DateTime $updatedAt
     */
    public function __construct(UuidInterface $uuid, DateTime $updatedAt)
    {
        $this->uuid = $uuid;
        $this->updatedAt = $updatedAt;
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'updated_at' => $this->updatedAt->toString(),
        ];
    }

    public static function deserialize(array $data): DomainEvent
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data,'updated_at');

        return new self(
            Uuid::fromString($data['uuid']),
            DateTime::fromString($data['updated_at'])
        );
    }
}