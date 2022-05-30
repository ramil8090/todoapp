<?php

declare(strict_types=1);


namespace Tests\UI\Cli\Query;


use App\Shared\Application\Query\QueryBusInterface;
use App\User\Application\Command\SignUp\SignUpCommand;
use Ramsey\Uuid\Uuid;
use Tests\UI\Cli\ConsoleTestCase;
use UI\Cli\Query\FindByEmailCommand;

class FindByEmailCommandTest extends ConsoleTestCase
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

        /** @var QueryBusInterface $queryBus */
        $queryBus = $this->service(QueryBusInterface::class);
        $command = new FindByEmailCommand($queryBus);
        $commandTester = $this->app($command, 'app:get-user-by-email');

        $commandTester->execute([
            'command' => $command->getName(),
            'email' => $email,
        ]);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString("User has found", $output);
        self::assertStringContainsString($email, $output);
    }
}