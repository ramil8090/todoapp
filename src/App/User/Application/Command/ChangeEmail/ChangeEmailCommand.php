<?php

declare(strict_types=1);


namespace App\User\Application\Command\ChangeEmail;


use App\Shared\Application\Command\CommandInterface;
use App\User\Domain\ValueObject\Email;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ChangeEmailCommand implements CommandInterface
{
    public UuidInterface $uuid;

    public Email $email;

    /**
     * @param string $uuid
     * @param string $email
     * @throws AssertionFailedException
     */
    public function __construct(string $uuid, string $email)
    {
        $this->uuid = Uuid::fromString($uuid);
        $this->email = Email::fromString($email);
    }

}