<?php

declare(strict_types=1);


namespace App\TodoList\Application\Command\CreateTask;


use App\Shared\Application\Command\CommandInterface;
use App\TodoList\Domain\ValueObject\TaskDescription;
use App\TodoList\Domain\ValueObject\TaskTitle;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class CreateTaskCommand implements CommandInterface
{
    public UuidInterface $uuid;
    public UuidInterface $todoListUuid;
    public TaskTitle $title;
    public TaskDescription $description;

    /**
     * @param string $uuid
     * @param string $todoListUuid
     * @param string $title
     * @param string $description
     * @throws AssertionFailedException
     */
    public function __construct(
        string $uuid,
        string $todoListUuid,
        string $title,
        string $description
    ) {
        $this->uuid = Uuid::fromString($uuid);
        $this->todoListUuid = Uuid::fromString($todoListUuid);
        $this->title = TaskTitle::fromString($title);
        $this->description = TaskDescription::fromString($description);
    }

}