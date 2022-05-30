<?php

declare(strict_types=1);


namespace Tests\App\TodoList\Domain;


use App\Shared\Domain\Exception\DateTimeException;
use App\TodoList\Domain\Event\TaskWasCompleted;
use App\TodoList\Domain\Event\TaskWasCreated;
use App\TodoList\Domain\Exception\TodoListNotExistException;
use App\TodoList\Domain\Task;
use App\TodoList\Domain\ValueObject\TaskDescription;
use App\TodoList\Domain\ValueObject\TaskState;
use App\TodoList\Domain\ValueObject\TaskTitle;
use Assert\AssertionFailedException;
use Monolog\Test\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\App\TodoList\Domain\Specification\DummyTodoListExistSpecification;

class TaskTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws DateTimeException
     * @throws AssertionFailedException
     */
    public function given_title_description_should_create_task_instance()
    {
        $uuid = Uuid::uuid4();
        $todoListUuid = Uuid::uuid4();
        $title = 'title';
        $description = 'description';

        $task = new Task(
            $uuid,
            $todoListUuid,
            TaskTitle::fromString($title),
            TaskDescription::fromString($description),
            new DummyTodoListExistSpecification(true)
        );

        self::assertSame($uuid, $task->uuid());
        self::assertSame($todoListUuid, $task->todoListUuid());
        self::assertSame($title, $task->title());
        self::assertSame($description, $task->description());
        self::assertTrue(TaskState::inProgress()->equalTo($task->state()));

        $events = $task->releaseEvents();
        self::assertCount(1, $events);
        self::assertInstanceOf(TaskWasCreated::class, $events[0]);
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public function given_non_exist_todolist_uuid_should_throw_an_exception()
    {
        self::expectException(TodoListNotExistException::class);

        new Task(
            Uuid::uuid4(),
            Uuid::uuid4(),
            TaskTitle::fromString('title'),
            TaskDescription::fromString('description'),
            new DummyTodoListExistSpecification(false)
        );
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public function should_change_the_task_state_to_completed()
    {
        $task = new Task(
            Uuid::uuid4(),
            Uuid::uuid4(),
            TaskTitle::fromString('title'),
            TaskDescription::fromString('description'),
            new DummyTodoListExistSpecification(true)
        );

        $task->setCompleted();

        self::assertTrue($task->state()->equalTo(TaskState::isCompleted()));
        self::assertNotNull($task->updatedAt());

        $events = $task->releaseEvents();
        self::assertCount(2, $events);
        self::assertInstanceOf(TaskWasCompleted::class, end($events));
    }
}