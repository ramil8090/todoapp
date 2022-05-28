<?php

declare(strict_types=1);


namespace Tests\App\User\Application\Command;


use App\Shared\Infrastructure\Event\Publisher\EventPublisherInterface;
use App\User\Application\Command\SignUp\SignUpCommand;
use App\User\Domain\Event\UserWasCreated;
use Ramsey\Uuid\Uuid;
use Tests\App\Shared\Application\ApplicationTestCase;

class SignUpHandlerTest extends ApplicationTestCase
{
    /**
     * @test
     *
     * @group integration
     */
    public function given_uuid_email_password_should_create_user()
    {
        $this->handle(new SignUpCommand(
            Uuid::uuid4()->toString(),
            'user@email.loc',
            'password'
        ));

        /** @var EventPublisherInterface $eventPublisher */
        $eventPublisher = $this->getContainer()->get(EventPublisherInterface::class);
        $events = $eventPublisher->events();

        self::assertCount(1, $events);

        $event = end($events);

        self::assertInstanceOf(UserWasCreated::class, $event);
    }
}