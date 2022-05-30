<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\ValueObject\DateTime;
use App\User\Domain\ValueObject\Auth\Credentials;
use Ramsey\Uuid\UuidInterface;

final class UserWasCreated implements DomainEvent
{
    public UuidInterface $uuid;

    public Credentials $credentials;

    public DateTime $createdAt;

    public function __construct(UuidInterface $uuid, Credentials $credentials, DateTime $createdAt)
    {
        $this->uuid = $uuid;
        $this->credentials = $credentials;
        $this->createdAt = $createdAt;
    }
}
