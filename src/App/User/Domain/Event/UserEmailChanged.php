<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\ValueObject\DateTime;
use App\User\Domain\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

final class UserEmailChanged implements DomainEvent
{
    public UuidInterface $uuid;

    public Email $email;

    public DateTime $updatedAt;

    public function __construct(UuidInterface $uuid, Email $email, DateTime $updatedAt)
    {
        $this->email = $email;
        $this->uuid = $uuid;
        $this->updatedAt = $updatedAt;
    }
}
