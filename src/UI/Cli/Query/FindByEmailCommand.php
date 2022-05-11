<?php

declare(strict_types=1);


namespace UI\Cli\Query;


use App\Shared\Application\Query\QueryBusInterface;
use App\User\Application\Query\FindByEmail\FindByEmailQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FindByEmailCommand extends Command
{
    private QueryBusInterface $queryBus;

    public function __construct(QueryBusInterface $queryBus)
    {
        parent::__construct();
        $this->queryBus = $queryBus;
    }

    protected function configure()
    {
        $this->setName("app:get-user-by-email")
            ->setDescription("Given email fetches an exist user.")
            ->addArgument('email', InputArgument::REQUIRED, 'User email');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $email */
        $email = $input->getArgument('email');

        $query = new FindByEmailQuery($email);

        $userData = $this->queryBus->ask($query);
        $userData = json_encode($userData);

        $output->writeln('User has found');
        $output->writeln('');
        $output->writeln($userData);

        return 1;
    }
}