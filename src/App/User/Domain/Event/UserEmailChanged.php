<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use App\User\Domain\ValueObject\Email;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
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

    /**
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public static function deserialize(array $data): self
    {
        Assertion::keyExists($data, 'uuid');
        Assertion::keyExists($data, 'email');

        return new self(
            Uuid::fromString($data['uuid']),
            Email::fromString($data['email']),
            DateTime::fromString($data['updated_at'])
        );
    }

    public function serialize(): array
    {
        return [
            'uuid' => $this->uuid->toString(),
            'email' => $this->email->toString(),
            'updated_at' => $this->updatedAt->toString(),
        ];
    }
}
