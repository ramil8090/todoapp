<?php

declare(strict_types=1);


namespace App\TodoList\Domain;


use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use App\TodoList\Domain\Event\TaskWasCompleted;
use App\TodoList\Domain\Event\TaskWasCreated;
use App\TodoList\Domain\Specification\TodoListExistSpecification;
use App\TodoList\Domain\ValueObject\TaskDescription;
use App\TodoList\Domain\ValueObject\TaskState;
use App\TodoList\Domain\ValueObject\TaskTitle;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="tasks")
 */
class Task extends AggregateRoot
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid_binary", unique=true)
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Column(type="uuid_binary")
     */
    private UuidInterface $todoListUuid;

    /**
     * @ORM\Column(type="task_title")
     */
    private TaskTitle $title;

    /**
     * @ORM\Column(type="task_description")
     */
    private TaskDescription $description;

    /**
     * @ORM\Column(type="task_state")
     */
    private TaskState $state;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTime $updatedAt;

    /**
     * @param UuidInterface $uuid
     * @param UuidInterface $todoListUuid
     * @param TaskTitle $title
     * @param TaskDescription $description
     * @param TodoListExistSpecification $specification
     * @param TaskState|null $state
     * @throws DateTimeException
     */
    public function __construct(
        UuidInterface $uuid,
        UuidInterface $todoListUuid,
        TaskTitle $title,
        TaskDescription $description,
        TodoListExistSpecification $specification,
        ?TaskState $state = null
    ) {
        $specification->isExist($todoListUuid);

        $this->uuid = $uuid;
        $this->todoListUuid = $todoListUuid;
        $this->title = $title;
        $this->description = $description;
        $this->state = $state ?? TaskState::inProgress();
        $this->createdAt = DateTime::now();

        $this->apply(new TaskWasCreated($uuid, DateTime::now()));
    }

    public function setCompleted(): void
    {
        $this->state = TaskState::isCompleted();
        $this->updatedAt = DateTime::now();
        $this->apply(new TaskWasCompleted($this->uuid, $this->updatedAt));
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function todoListUuid(): UuidInterface
    {
        return $this->todoListUuid;
    }

    public function title(): string
    {
        return $this->title->toString();
    }

    public function description(): string
    {
        return $this->description->toString();
    }

    public function state(): TaskState
    {
        return $this->state;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }
}