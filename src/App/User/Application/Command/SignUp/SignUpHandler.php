<?php

declare(strict_types=1);


namespace App\User\Application\Command\SignUp;


use App\Shared\Application\Command\CommandHandlerInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\User\Domain\User;

class SignUpHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;

    private UniqueEmailSpecificationInterface $uniqueEmailSpecification;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param UniqueEmailSpecificationInterface $uniqueEmailSpecification
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $this->userRepository = $userRepository;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
    }

    public function __invoke(SignUpCommand $command)
    {
        $user = User::create($command->uuid, $command->credentials, $this->uniqueEmailSpecification);

        $this->userRepository->store($user);
    }
}