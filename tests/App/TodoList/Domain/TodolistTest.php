<?php

declare(strict_types=1);


namespace App\TodoList\Domain;


use App\Shared\Domain\Exception\DateTimeException;
use App\TodoList\Domain\Event\TodoListTitleChanged;
use App\TodoList\Domain\Event\TodoListWasCreated;
use App\TodoList\Domain\Exception\OwnerNotExistException;
use App\TodoList\Domain\ValueObject\Owner;
use App\TodoList\Domain\ValueObject\TodoListTitle;
use App\User\Domain\ValueObject\Email;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\App\TodoList\Domain\Specification\DummyOwnerExistSpecification;

class TodoListTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws AssertionFailedException
     */
    public function given_a_title_it_should_create_a_todolist_instance()
    {
        $title = "Todo List Title";
        $ownerEmail = "user@email.loc";

        $todolist = TodoList::create(
            Uuid::uuid4(),
            new Owner(Email::fromString($ownerEmail)),
            new TodoListTitle($title),
            new DummyOwnerExistSpecification(true)
        );

        self::assertSame($title, $todolist->title());
        self::assertSame($ownerEmail, $todolist->ownerEmail());

        $events = $todolist->releaseEvents();

        self::assertCount(1,  $events, 'Only one event should be in the butter');

        /** @var TodoListWasCreated $event */
        $event = $events[0];
        self::assertInstanceOf(TodoListWasCreated::class, $event, 'First event should be TodoListWasCreated');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public function given_new_title_should_change_one_in_existing_todolist()
    {
        $title = "Todo List Title";
        $ownerEmail = "user@email.loc";

        $todolist = TodoList::create(
            Uuid::uuid4(),
            new Owner(Email::fromString($ownerEmail)),
            new TodoListTitle($title),
            new DummyOwnerExistSpecification(true)
        );

        $newTitle = "New Todo List Title";
        $todolist->changeTitle(new TodoListTitle($newTitle));

        self::assertSame($newTitle, $todolist->title());

        $events = $todolist->releaseEvents();
        $event = end($events);

        self::assertInstanceOf(TodoListTitleChanged::class, $event, 'An event  of title changing should be TodoListChanged');
    }

    /**
     * @test
     *
     * @group unit
     * @throws AssertionFailedException
     */
    public function given_not_exist_email_should_throw_exception()
    {
        $this->expectException(OwnerNotExistException::class);

        $title = "Todo List Title";
        $ownerEmail = "user@email.loc";

        TodoList::create(
            Uuid::uuid4(),
            new Owner(Email::fromString($ownerEmail)),
            new TodoListTitle($title),
            new DummyOwnerExistSpecification(false)
        );
    }
}