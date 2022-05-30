<?php

declare(strict_types=1);


namespace UI\Cli\Command;


use App\Shared\Application\Command\CommandBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SignInCommand extends Command
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    protected function configure()
    {
        $this->setName("app:sign-in-user")
            ->setDescription("Given email and password log in an user.")
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $email */
        $email = $input->getArgument('email');

        /** @var string $password */
        $password = $input->getArgument('password');

        $command = new \App\User\Application\Command\SignIn\SignInCommand(
            $email,
            $password
        );

        $this->commandBus->handle($command);

        $output->writeln("User $email sign in successfully");

        return 1;
    }
}