<?php

declare(strict_types=1);


namespace Tests\UI\Cli\Command;


use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Query\Item;
use App\User\Application\Command\SignUp\SignUpCommand;
use App\User\Application\Query\FindByEmail\FindByEmailQuery;
use Ramsey\Uuid\Uuid;
use Tests\UI\Cli\ConsoleTestCase;
use UI\Cli\Command\CreateUserCommand;
use UI\Cli\Command\SignInCommand;

class SignInCommandTest extends ConsoleTestCase
{
    /**
     * @test
     *
     * @group e2e
     *
     * @throws \Throwable
     */
    public function given_credentials_should_sign_in_user()
    {
        $email = 'user@email.com';
        $password = 'password';

        $this->handle(new SignUpCommand(
            Uuid::uuid4()->toString(),
            $email,
            $password
        ));

        /** @var CommandBusInterface $commandBus */
        $commandBus = $this->service(CommandBusInterface::class);
        $command = new SignInCommand($commandBus);
        $commandTester = $this->app($command, 'app:sign-in-user');

        $commandTester->execute([
            'command' => $command->getName(),
            'email' => $email,
            'password' => $password,
        ]);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString("User $email sign in successfully", $output);
    }
}