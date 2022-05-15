<?php

declare(strict_types=1);


namespace App\User\Application\Command\ChangeEmail;


use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Infrastructure\Event\Publisher\EventDispatcherInterface;
use App\Shared\Infrastructure\Persistence\Exception\NotFoundException;
use App\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\User\Infrastructure\Repository\UserRepository;

class ChangeEmailHandler implements CommandHandlerInterface
{
    private UserRepository $userRepository;

    private UniqueEmailSpecificationInterface $uniqueEmailSpecification;

    private EventDispatcherInterface $eventDispatcher;

    /**
     * @param UserRepository $userRepository
     * @param UniqueEmailSpecificationInterface $uniqueEmailSpecification
     */
    public function __construct(
        UserRepository $userRepository,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->uniqueEmailSpecification = $uniqueEmailSpecification;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws NotFoundException
     * @throws DateTimeException
     */
    public function __invoke(ChangeEmailCommand $command)
    {
        $user = $this->userRepository->get($command->uuid);

        $user->changeEmail($command->email, $this->uniqueEmailSpecification);

        $this->userRepository->store($user);

        $this->eventDispatcher->dispatch($user->releaseEvents());
    }
}