<?php

declare(strict_types=1);


namespace App\TodoList\Domain\Event;


use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\ValueObject\DateTime;
use Ramsey\Uuid\UuidInterface;

class TaskWasCompleted implements DomainEvent
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


}