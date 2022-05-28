<?php

declare(strict_types=1);


namespace Tests\App\TodoList\Application\Command;


use App\Shared\Infrastructure\Event\Publisher\EventPublisherInterface;
use App\TodoList\Application\Command\CreateTodoList\CreateTodoListCommand;
use App\TodoList\Domain\Event\TodoListWasCreated;
use App\User\Application\Command\SignUp\SignUpCommand;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Tests\App\Shared\Application\ApplicationTestCase;
use Throwable;

class CreateTodoListHandleTest extends ApplicationTestCase
{
    const USER_EMAIL = 'user@email.com';

    /**
     * @test
     *
     * @group integration
     *
     * @throws AssertionFailedException
     * @throws Throwable
     */
    public function given_uuid_title_owner_should_create_todo_list()
    {
        $this->handle(new CreateTodoListCommand(
            Uuid::uuid4()->toString(),
            'todolist title',
            self::USER_EMAIL
        ));

        /** @var EventPublisherInterface $eventPublisher */
        $eventPublisher = $this->getContainer()->get(EventPublisherInterface::class);
        $events = $eventPublisher->events();

        self::assertCount(2, $events);

        $event = end($events);

        self::assertInstanceOf(TodoListWasCreated::class, $event);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->handle(new SignUpCommand(
            Uuid::uuid4()->toString(),
            self::USER_EMAIL,
            'password',
        ));
    }
}