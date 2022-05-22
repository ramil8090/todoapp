<?php

declare(strict_types=1);


namespace App\TodoList\Domain\Event;


use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\ValueObject\DateTime;
use Assert\Assertion;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class TodoListWasCreated implements DomainEvent
{
    public UuidInterface $uuid;
    public DateTime $createdAt;

    /**
     * @param UuidInterface $uuid
     * @param DateTime $createdAt
     */
    public function __construct(UuidInterface $uuid, DateTime $createdAt)
    {
        $this->uuid = $uuid;
        $this->createdAt = $createdAt;
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'created_at' => $this->createdAt->toString(),
        ];
    }

    public static function deserialize(array $data): DomainEvent
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'created_at');

        return new self(Uuid::fromString($data['uuid']), DateTime::fromString($data['created_at']));
    }
}