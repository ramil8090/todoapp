<?php

declare(strict_types=1);


namespace App\TodoList\Application\Command\ChangeTodoListTitle;


use App\Shared\Application\Command\CommandInterface;
use App\TodoList\Domain\ValueObject\TodoListTitle;
use App\User\Domain\ValueObject\Email;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ChangeTodoListTitleCommand implements CommandInterface
{
    public UuidInterface $uuid;
    public TodoListTitle $title;
    public Email $userEmail;

    /**
     * @param string $uuid
     * @param string $title
     * @param string $userEmail
     * @throws AssertionFailedException
     */
    public function __construct(string $uuid, string $title, string $userEmail)
    {
        $this->uuid = Uuid::fromString($uuid);
        $this->title = new TodoListTitle($title);
        $this->userEmail = Email::fromString($userEmail);
    }


}