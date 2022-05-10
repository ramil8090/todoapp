<?php

declare(strict_types=1);


namespace UI\Cli\Command;


use App\User\Application\Command\SignUp\SignUpHandler as CreateUserHandler;
use App\User\Application\Command\SignUp\SignUpCommand as CreateUser;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Specification\UniqueEmailSpecificationInterface;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CreateUserCommand extends Command
{
    private UserRepositoryInterface $userRepository;
    private UniqueEmailSpecificationInterface $uniqueEmailSpecification;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
    }

    protected function configure()
    {
        $this->setName("app:create-user")
            ->setDescription("Given email and password generates a new user.")
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addArgument('uuid', InputArgument::OPTIONAL, 'User uuid');
    }

    /**
     * @throws AssertionFailedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var string $uuid */
        $uuid = $input->getArgument('uuid') ?? Uuid::uuid4()->toString();
        /** @var string $email */
        $email = $input->getArgument('email');
        /** @var string $password */
        $password = $input->getArgument('password');

        $createUserHandler = new CreateUserHandler(
            $this->userRepository,
            $this->uniqueEmailSpecification
        );

        $createUserHandler(new CreateUser(
            $uuid,
            $email,
            $password
        ));

        $output->writeln('<info>User Created: </info>');
        $output->writeln('');
        $output->writeln("Uuid: $uuid");
        $output->writeln("Email: $email");

        return 1;
    }
}