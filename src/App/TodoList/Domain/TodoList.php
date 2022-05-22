<?php

declare(strict_types=1);


namespace App\TodoList\Domain;


use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use App\TodoList\Domain\Event\TodoListTitleChanged;
use App\TodoList\Domain\Event\TodoListWasCreated;
use App\TodoList\Domain\Specification\OwnerExistSpecification;
use App\TodoList\Domain\ValueObject\Owner;
use App\TodoList\Domain\ValueObject\TodoListTitle;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="todolists")
 */
class TodoList extends AggregateRoot
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid_binary", unique="true")
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Embedded(class=Owner::class, columnPrefix=false)
     */
    private Owner $owner;

    /**
     * @ORM\Column(type="todolist_title")
     */
    private TodoListTitle $title;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private DateTime $updatedAt;

    public static function create(
        UuidInterface $uuid,
        Owner $owner,
        TodoListTitle $title,
        OwnerExistSpecification $ownerExistSpecification
    ): self
    {
        $ownerExistSpecification->isExist($owner->email);

        $todoList = new self();
        $todoList->uuid = $uuid;
        $todoList->owner = $owner;
        $todoList->title = $title;
        $todoList->createdAt = DateTime::now();

        $todoList->apply(new TodoListWasCreated($uuid, DateTime::now()));

        return $todoList;
    }

    public function title(): string
    {
        return $this->title->toString();
    }

    public function ownerEmail(): string
    {
        return $this->owner->email->toString();
    }

    /**
     * @throws DateTimeException
     */
    public function changeTitle(TodoListTitle $title): void
    {
        $this->title = $title;
        $this->updatedAt = DateTime::now();
        $this->apply(new TodoListTitleChanged($this->uuid, DateTime::now()));
    }
}