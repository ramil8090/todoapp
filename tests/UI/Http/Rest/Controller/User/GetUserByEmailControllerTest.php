<?php

declare(strict_types=1);


namespace Tests\UI\Http\Rest\Controller\User;


use App\Shared\Infrastructure\Event\Publisher\EventPublisherInterface;
use Symfony\Component\HttpFoundation\Response;
use Tests\UI\Http\Rest\Controller\JsonApiTestCase;

class GetUserByEmailControllerTest extends JsonApiTestCase
{
    /**
     * @test
     *
     * @group e2e
     */
    public function given_an_exist_user_email_should_return_a_200_status_code(): void
    {
        $this->get("/api/user/" . self::DEFAULT_EMAIL);

        self::assertSame(Response::HTTP_OK, $this->cli->getResponse()->getStatusCode());

        $response = \json_decode($this->cli->getResponse()->getContent(), true);
        self::assertArrayHasKey('data', $response);
        self::assertArrayHasKey('id', $response['data']);
        self::assertArrayHasKey('type', $response['data']);
        self::assertArrayHasKey('attributes', $response['data']);
        self::assertArrayHasKey('uuid', $response['data']['attributes']);
        self::assertArrayHasKey('credentials.email', $response['data']['attributes']);
        self::assertArrayHasKey('createdAt', $response['data']['attributes']);
        self::assertEquals(self::DEFAULT_EMAIL, $response['data']['attributes']['credentials.email']);

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
    public function invalid_input_parameters_should_return_a_400_status_code(): void
    {
        $this->get('/api/user/user@');

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
    public function given_non_exist_user_should_return_a_404_status_code(): void
    {
        $this->get('/api/user/non-exist-user@email.com');

        self::assertSame(Response::HTTP_NOT_FOUND, $this->cli->getResponse()->getStatusCode());

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