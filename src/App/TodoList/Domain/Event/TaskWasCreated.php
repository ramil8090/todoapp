<?php

declare(strict_types=1);


namespace App\TodoList\Domain\Event;


use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\ValueObject\DateTime;
use Ramsey\Uuid\UuidInterface;

class TaskWasCreated implements DomainEvent
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


}