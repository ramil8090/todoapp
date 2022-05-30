<?php

declare(strict_types=1);


namespace Tests\UI\Cli\Command;


use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\Item;
use App\User\Application\Query\FindByEmail\FindByEmailQuery;
use Ramsey\Uuid\Uuid;
use Tests\UI\Cli\ConsoleTestCase;
use UI\Cli\Command\CreateUserCommand;

class CreateUserCommandTest extends ConsoleTestCase
{
    /**
     * @test
     *
     * @group e2e
     */
    public function given_credentials_should_create_user()
    {
        $email = 'user@email.com';

        /** @var CommandBusInterface $commandBus */
        $commandBus = $this->service(CommandBusInterface::class);
        $command = new CreateUserCommand($commandBus);
        $commandTester = $this->app($command, 'app:create-user');

        $commandTester->execute([
            'command' => $command->getName(),
            'uuid' => Uuid::uuid4()->toString(),
            'email' => $email,
            'password' => 'password',
        ]);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('User Created:', $output);
        self::assertStringContainsString('Email: ' . $email, $output);

        /** @var Item $result */
        $result = $this->ask(new FindByEmailQuery($email));
        self::assertInstanceOf(Item::class, $result);
        self::assertSame('User', $result->type);
        self::assertSame($email, $result->resource['credentials.email']->toString());
    }
}