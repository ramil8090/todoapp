<?php

declare(strict_types=1);


namespace Tests\App\TodoList\Application\Command;


use App\Shared\Infrastructure\Event\Publisher\EventPublisherInterface;
use App\TodoList\Application\Command\ChangeTodoListTitle\ChangeTodoListTitleCommand;
use App\TodoList\Application\Command\CreateTodoList\CreateTodoListCommand;
use App\TodoList\Domain\Event\TodoListTitleChanged;
use App\TodoList\Domain\Exception\NotOwnerException;
use App\User\Application\Command\SignUp\SignUpCommand;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tests\App\Shared\Application\ApplicationTestCase;
use Throwable;

class ChangeTodoListTitleHandleTest extends ApplicationTestCase
{
    const USER_EMAIL = 'user@email.com';

    private string $todoListUuid;

    /**
     * @test
     *
     * @group integration
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function given_uuid_title_user_email_should_change_the_todo_list_title()
    {
        $this->handle(new ChangeTodoListTitleCommand(
            $this->todoListUuid,
            'new todo list title',
            self::USER_EMAIL
        ));

        /** @var EventPublisherInterface $eventPublisher */
        $eventPublisher = $this->getContainer()->get(EventPublisherInterface::class);
        $events = $eventPublisher->events();

        self::assertCount(3, $events);

        $event = end($events);

        self::assertInstanceOf(TodoListTitleChanged::class, $event);
    }

    /**
     * @test
     *
     * @group integration
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function given_non_owner_email_on_changing_task_title_should_throw_an_exception()
    {
        self::expectException(NotOwnerException::class);

        $this->handle(new ChangeTodoListTitleCommand(
            $this->todoListUuid,
            'new todo list title',
            'non-owner@email.com'
        ));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $userUuid = Uuid::uuid4()->toString();

        $this->handle(new SignUpCommand(
            $userUuid,
            self::USER_EMAIL,
            'password',
        ));

        $this->todoListUuid = Uuid::uuid4()->toString();

        $this->handle(new CreateTodoListCommand(
            $this->todoListUuid,
            'todo list title',
            self::USER_EMAIL
        ));
    }
}