<?php

declare(strict_types=1);


namespace Tests\App\TodoList\Application\Command;


use App\Shared\Infrastructure\Event\Publisher\EventPublisherInterface;
use App\TodoList\Application\Command\ChangeTodoListTitle\ChangeTodoListTitleCommand;
use App\TodoList\Application\Command\CompleteTask\CompleteTaskCommand;
use App\TodoList\Application\Command\CreateTask\CreateTaskCommand;
use App\TodoList\Application\Command\CreateTodoList\CreateTodoListCommand;
use App\TodoList\Domain\Event\TaskWasCompleted;
use App\TodoList\Domain\Event\TaskWasCreated;
use App\TodoList\Domain\Event\TodoListTitleChanged;
use App\TodoList\Domain\Exception\NotOwnerException;
use App\User\Application\Command\SignUp\SignUpCommand;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tests\App\Shared\Application\ApplicationTestCase;
use Throwable;

class CompleteTaskHandleTest extends ApplicationTestCase
{
    const USER_EMAIL = 'user@email.com';

    private string $taskUuid;

    /**
     * @test
     *
     * @group integration
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function given_required_parameters_should_create_task()
    {
        $this->handle(new CompleteTaskCommand(
            $this->taskUuid,
            self::USER_EMAIL,
        ));

        /** @var EventPublisherInterface $eventPublisher */
        $eventPublisher = $this->getContainer()->get(EventPublisherInterface::class);
        $events = $eventPublisher->events();

        self::assertCount(4, $events);

        $event = end($events);

        self::assertInstanceOf(TaskWasCompleted::class, $event);
    }

    /**
     * @test
     *
     * @group integration
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function given_wrong_todo_list_owner_email_should_throw_an_exception()
    {
        self::expectException(NotOwnerException::class);

        $this->handle(new CompleteTaskCommand(
            $this->taskUuid,
            'non-exist@email.com'
        ));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $userUuid = Uuid::uuid4()->toString();
        $todoListUuid = Uuid::uuid4()->toString();
        $this->taskUuid = Uuid::uuid4()->toString();

        $this->handle(new SignUpCommand(
            $userUuid,
            self::USER_EMAIL,
            'password',
        ));

        $this->handle(new CreateTodoListCommand(
            $todoListUuid,
            'todo list title',
            self::USER_EMAIL
        ));

        $this->handle(new CreateTaskCommand(
            $this->taskUuid,
            $todoListUuid,
            'task title',
            'description'
        ));
    }
}