<?php

declare(strict_types=1);


namespace Tests\UI\Http\Rest\Controller\User;


use App\Shared\Infrastructure\Event\Publisher\EventPublisherInterface;
use App\User\Domain\Event\UserEmailChanged;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\UI\Http\Rest\Controller\JsonApiTestCase;

class UserChangeEmailControllerTest extends JsonApiTestCase
{
    /**
     * @test
     *
     * @group e2e
     */
    public function given_valid_user_uuid_and_email_should_return_a_200_status_code(): void
    {
        $this->post("/api/users/{$this->userUuid}/email", [
            'email' => 'new-email-of-the-user@email.com'
        ]);

        self::assertSame(Response::HTTP_OK, $this->cli->getResponse()->getStatusCode());

        /** @var EventPublisherInterface $events */
        $eventPublisher = self::getContainer()->get(EventPublisherInterface::class);
        $events = $eventPublisher->events();

        self::assertCount(1, $events);
        self::assertInstanceOf(UserEmailChanged::class, $events[0]);
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function trying_of_changing_an_email_of_other_user_should_return_403_status_code(): void
    {
        $this->post("/api/users/". Uuid::uuid4()->toString() ."/email", [
            'email' => 'other-user@email.com'
        ]);

        self::assertSame(Response::HTTP_FORBIDDEN, $this->cli->getResponse()->getStatusCode());

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
    public function given_a_invalid_email_should_return_a_400_status_code(): void
    {
        $this->post("/api/users/{$this->userUuid->toString()}/email", [
            'email' => 'email.com'
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->cli->getResponse()->getStatusCode());

        /** @var EventPublisherInterface $events */
        $eventPublisher = self::getContainer()->get(EventPublisherInterface::class);
        $events = $eventPublisher->events();

        self::assertCount(0, $events);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->createUser();
        $this->auth();
    }
}