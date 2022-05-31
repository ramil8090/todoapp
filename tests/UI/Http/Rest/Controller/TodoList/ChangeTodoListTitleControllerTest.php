<?php

declare(strict_types=1);


namespace Tests\UI\Http\Rest\Controller\TodoList;


use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Infrastructure\Event\Publisher\EventPublisherInterface;
use App\TodoList\Application\Command\CreateTodoList\CreateTodoListCommand;
use App\TodoList\Domain\Event\TodoListTitleChanged;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Response;
use Tests\UI\Http\Rest\Controller\JsonApiTestCase;

class ChangeTodoListTitleControllerTest extends JsonApiTestCase
{
    private UuidInterface $todoListUuid;

    /**
     * @test
     *
     * @group e2e
     */
    public function given_a_non_empty_title_should_return_200_status_code(): void
    {
        $this->post("/api/todolist/{$this->todoListUuid->toString()}/title", [
            'title' => 'A new todo list\'s title'
        ]);

        self::assertSame(Response::HTTP_OK, $this->cli->getResponse()->getStatusCode());

        /** @var EventPublisherInterface $events */
        $eventPublisher = self::getContainer()->get(EventPublisherInterface::class);
        $events = $eventPublisher->events();

        self::assertCount(1, $events);
        self::assertInstanceOf(TodoListTitleChanged::class, $events[0]);
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function given_an_empty_title_should_return_400_status_code(): void
    {
        $this->post("/api/todolist/{$this->todoListUuid->toString()}/title", [
            'title' => ''
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->cli->getResponse()->getStatusCode());

        /** @var EventPublisherInterface $events */
        $eventPublisher = self::getContainer()->get(EventPublisherInterface::class);
        $events = $eventPublisher->events();

        self::assertCount(0, $events);
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function given_a_todo_list_uuid_that_doesnt_belong_to_user_should_return_400_status_code(): void
    {
        $this->post("/api/todolist/". Uuid::uuid4()->toString() ."/title", [
            'title' => ''
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->cli->getResponse()->getStatusCode());

        /** @var EventPublisherInterface $events */
        $eventPublisher = self::getContainer()->get(EventPublisherInterface::class);
        $events = $eventPublisher->events();

        self::assertCount(0, $events);
    }

    /**
     * @throws AssertionFailedException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->createUser();
        $this->auth();
        $this->createTodoList();
        $this->fireTerminateEvent();
    }

    /**
     * @throws AssertionFailedException
     */
    private function createTodoList(): void
    {
        $this->todoListUuid = Uuid::uuid4();

        /** @var CommandBusInterface $commandBus */
        $commandBus = self::getContainer()->get(CommandBusInterface::class);

        $commandBus->handle(new CreateTodoListCommand(
            $this->todoListUuid->toString(),
            'Todo list title',
            self::DEFAULT_EMAIL
        ));
    }
}