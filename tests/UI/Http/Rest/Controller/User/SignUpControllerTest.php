<?php

declare(strict_types=1);


namespace Tests\UI\Http\Rest\Controller\User;


use App\Shared\Infrastructure\Event\Publisher\EventPublisherInterface;
use App\User\Domain\Event\UserWasCreated;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\UI\Http\Rest\Controller\JsonApiTestCase;

class SignUpControllerTest extends JsonApiTestCase
{
    /**
     * @test
     *
     * @group e2e
     */
    public function given_a_valid_uuid_email_password_should_return_a_201_status_code(): void
    {
        $this->post('/api/signup', [
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'new-user@email.com',
            'password' => 'asdf123456',
        ]);

        self::assertSame(Response::HTTP_CREATED, $this->cli->getResponse()->getStatusCode());

        /** @var EventPublisherInterface $events */
        $eventPublisher = self::getContainer()->get(EventPublisherInterface::class);
        $events = $eventPublisher->events();

        self::assertCount(1, $events);
        self::assertInstanceOf(UserWasCreated::class, $events[0]);
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function given_email_of_exist_user_should_return_a_400_status_code(): void
    {
        $this->createUser();

        $this->post('/api/signup', [
            'uuid' => Uuid::uuid4()->toString(),
            'email' => self::DEFAULT_EMAIL,
            'password' => 'asdf123456',
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
    public function given_invalid_email_should_return_a_400_status_code(): void
    {
        $this->post('/api/signup', [
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'user@',
            'password' => 'asdf123456',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->cli->getResponse()->getStatusCode());

        /** @var EventPublisherInterface $events */
        $eventPublisher = self::getContainer()->get(EventPublisherInterface::class);
        $events = $eventPublisher->events();

        self::assertCount(0, $events);
    }
}