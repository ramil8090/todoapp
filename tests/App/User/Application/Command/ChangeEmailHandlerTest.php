<?php

declare(strict_types=1);


namespace Tests\App\User\Application\Command;


use App\Shared\Infrastructure\Event\Publisher\EventPublisherInterface;
use App\User\Application\Command\ChangeEmail\ChangeEmailCommand;
use App\User\Application\Command\SignUp\SignUpCommand;
use App\User\Domain\Event\UserEmailChanged;
use Ramsey\Uuid\Uuid;
use Tests\App\Shared\Application\ApplicationTestCase;

class ChangeEmailHandlerTest extends ApplicationTestCase
{
    private string $userUuid;

    /**
     * @test
     *
     * @group integration
     */
    public function given_new_email_should_change_the_old_one()
    {
        $this->handle(new ChangeEmailCommand($this->userUuid, 'user@new-email.com'));

        /** @var EventPublisherInterface $eventPublisher */
        $eventPublisher = $this->getContainer()->get(EventPublisherInterface::class);
        $events = $eventPublisher->events();

        self::assertCount(2, $events);

        $event = end($events);

        self::assertInstanceOf(UserEmailChanged::class, $event);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->handle(new SignUpCommand(
            $uuid = Uuid::uuid4()->toString(),
            'user@email.com',
            'password',
        ));

        $this->userUuid = $uuid;
    }
}