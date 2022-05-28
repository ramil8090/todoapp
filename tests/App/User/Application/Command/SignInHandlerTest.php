<?php

declare(strict_types=1);


namespace Tests\App\User\Application\Command;


use App\Shared\Infrastructure\Event\Publisher\EventPublisherInterface;
use App\User\Application\Command\SignIn\SignInCommand;
use App\User\Application\Command\SignUp\SignUpCommand;
use App\User\Domain\Event\UserSignedIn;
use Ramsey\Uuid\Uuid;
use Tests\App\Shared\Application\ApplicationTestCase;

class SignInHandlerTest extends ApplicationTestCase
{
    const USER_EMAIL = 'user@email.com';
    const USER_PASS = 'password';

    /**
     * @test
     *
     * @group integration
     */
    public function given_email_password_should_sign_in_user()
    {
        $this->handle(new SignInCommand(self::USER_EMAIL, self::USER_PASS));

        /** @var EventPublisherInterface $eventPublisher */
        $eventPublisher = $this->getContainer()->get(EventPublisherInterface::class);
        $events = $eventPublisher->events();

        self::assertCount(2, $events);

        $event = end($events);

        self::assertInstanceOf(UserSignedIn::class, $event);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->handle(new SignUpCommand(
            Uuid::uuid4()->toString(),
            self::USER_EMAIL,
            self::USER_PASS,
        ));
    }
}