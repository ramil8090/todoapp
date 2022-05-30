<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;
use App\User\Domain\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

final class UserSignedIn implements DomainEvent
{
    public Email $email;

    public UuidInterface $uuid;

    public function __construct(UuidInterface $uuid, Email $email)
    {
        $this->uuid = $uuid;
        $this->email = $email;
    }
}
