<?php

declare(strict_types=1);


namespace App\User\Application\Command\SignUp;


use App\Shared\Application\Command\CommandInterface;
use App\User\Domain\ValueObject\Auth\Credentials;
use App\User\Domain\ValueObject\Auth\HashedPassword;
use App\User\Domain\ValueObject\Email;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class SignUpCommand implements CommandInterface
{
    public UuidInterface $uuid;

    public Credentials $credentials;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(string $uuid, string $email, string $plainPassword)
    {
        $this->uuid = Uuid::fromString($uuid);
        $this->credentials = new Credentials(Email::fromString($email), HashedPassword::encode($plainPassword));
    }
}