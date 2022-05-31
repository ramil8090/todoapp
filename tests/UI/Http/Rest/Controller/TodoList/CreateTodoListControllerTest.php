<?php

declare(strict_types=1);


namespace Tests\UI\Http\Rest\Controller\TodoList;


use App\Shared\Infrastructure\Event\Publisher\EventPublisherInterface;
use App\TodoList\Domain\Event\TodoListWasCreated;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\UI\Http\Rest\Controller\JsonApiTestCase;

class CreateTodoListControllerTest extends JsonApiTestCase
{
    /**
     * @test
     *
     * @group e2e
     */
    public function given_uuid_title_should_return_a_201_status_code(): void
    {
        $this->post('/api/todolist', [
           'uuid' => Uuid::uuid4()->toString(),
           'title' => 'Todo list title'
        ]);

        self::assertSame(Response::HTTP_CREATED, $this->cli->getResponse()->getStatusCode());

        /** @var EventPublisherInterface $events */
        $eventPublisher = self::getContainer()->get(EventPublisherInterface::class);
        $events = $eventPublisher->events();

        self::assertCount(1, $events);
        self::assertInstanceOf(TodoListWasCreated::class, $events[0]);
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function given_empty_title_should_return_a_400_status_code(): void
    {
        $this->post('/api/todolist', [
            'uuid' => Uuid::uuid4()->toString(),
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
    public function for_unauthorized_users_should_return_401_status_code(): void
    {
        $this->logout();

        $this->post('/api/todolist', [
            'uuid' => Uuid::uuid4()->toString(),
            'title' => 'Todo list title'
        ]);

        self::assertSame(Response::HTTP_UNAUTHORIZED, $this->cli->getResponse()->getStatusCode());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->createUser();
        $this->auth();
        $this->fireTerminateEvent();
    }
}