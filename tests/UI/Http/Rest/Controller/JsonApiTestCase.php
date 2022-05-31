<?php

declare(strict_types=1);


namespace Tests\UI\Http\Rest\Controller;


use App\Shared\Application\Command\CommandBusInterface;
use App\User\Application\Command\SignUp\SignUpCommand;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;

abstract class JsonApiTestCase extends WebTestCase
{
    const DEFAULT_EMAIL = 'user@email.com';

    const DEFAULT_PASS = 'password';

    protected ?KernelBrowser $cli;

    protected ?UuidInterface $userUuid;

    protected ?string $token = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cli = static::createClient();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->cli = null;
        $this->token = null;
        $this->userUuid = null;
    }

    protected function createUser(string $email = self::DEFAULT_EMAIL, string $password = self::DEFAULT_PASS): string
    {
        $this->userUuid =  Uuid::uuid4();

        $signUp = new SignUpCommand(
            $this->userUuid->toString(),
            $email,
            $password
        );

        /** @var CommandBusInterface $commandBus */
        $commandBus = self::getContainer()->get(CommandBusInterface::class);

        $commandBus->handle($signUp);

        return $email;
    }

    protected function headers(): array
    {
        $headers = [
            'CONTENT_TYPE' => 'application/json',
        ];

        if ($this->token) {
            $headers['HTTP_Authorization'] = 'Bearer ' . $this->token;
        }

        return $headers;
    }

    protected function post(string $uri, array $content): void
    {
        $this->cli->request(
            Request::METHOD_POST,
            $uri,
            [],
            [],
            $this->headers(),
            (string) \json_encode($content)
        );
    }

    protected function get(string $uri, array $parameters = []): void
    {
        $this->cli->request(
            Request::METHOD_GET,
            $uri,
            $parameters,
            [],
            $this->headers()
        );
    }

    protected function auth(string $username = self::DEFAULT_EMAIL, $password = self::DEFAULT_PASS): void
    {
        $this->post('api/auth_check', [
            '_username' => $username,
            '_password' => $password,
        ]);

        /** @var string $content */
        $content = $this->cli->getResponse()->getContent();

        $response = \json_decode($content, true);

        $this->token = $response['token'];
    }

    protected function logout(): void
    {
        $this->token = null;
    }

    protected function fireTerminateEvent(): void
    {
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->cli->getContainer()->get('event_dispatcher');

        $dispatcher->dispatch(
            new TerminateEvent(
                static::$kernel,
                Request::create('/'),
                new Response()
            ),
            KernelEvents::TERMINATE
        );
    }
}