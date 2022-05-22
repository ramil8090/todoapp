<?php

declare(strict_types=1);


namespace App\TodoList\Application\Command\CreateTodoList;


use App\Shared\Application\Command\CommandInterface;
use App\TodoList\Domain\ValueObject\Owner;
use App\TodoList\Domain\ValueObject\TodoListTitle;
use App\User\Domain\ValueObject\Email;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CreateTodoListCommand implements CommandInterface
{
    public UuidInterface $uuid;

    public TodoListTitle $title;

    public Owner $owner;

    /**
     * @param string $uuid
     * @param string $title
     * @param string $ownerEmail
     * @throws AssertionFailedException
     */
    public function __construct(string $uuid, string $title, string $ownerEmail)
    {
        $this->uuid = Uuid::fromString($uuid);
        $this->title = new TodoListTitle($title);
        $this->owner = new Owner(Email::fromString($ownerEmail));
    }
}