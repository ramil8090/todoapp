<?php

declare(strict_types=1);


namespace App\TodoList\Application\Command\CompleteTask;


use App\Shared\Application\Command\CommandInterface;
use App\User\Domain\ValueObject\Email;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CompleteTaskCommand implements CommandInterface
{
    public UuidInterface $uuid;
    public Email $userEmail;

    /**
     * @throws AssertionFailedException
     */
    public function __construct(string $uuid, string $userEmail)
    {
        $this->uuid = Uuid::fromString($uuid);
        $this->userEmail = Email::fromString($userEmail);
    }
}