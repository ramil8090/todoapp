<?php

declare(strict_types=1);


namespace App\User\Application\Command\SignIn;


use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Infrastructure\Event\Publisher\EventDispatcherInterface;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Repository\CheckUserByEmailInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

class SignInHandler implements CommandHandlerInterface
{
    private EventDispatcherInterface $eventDispatcher;

    private UserRepositoryInterface $userRepository;

    private CheckUserByEmailInterface $checkUserByEmail;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param UserRepositoryInterface $userRepository
     * @param CheckUserByEmailInterface $checkUserByEmail
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        UserRepositoryInterface $userRepository,
        CheckUserByEmailInterface $checkUserByEmail)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userRepository = $userRepository;
        $this->checkUserByEmail = $checkUserByEmail;
    }

    public function __invoke(SignInCommand $command)
    {
        $uuid = $this->uuidFromEmail($command->email);

        $user = $this->userRepository->get($uuid);

        $user->signIn($command->plainPassword);

        $this->eventDispatcher->dispatch($user->releaseEvents());
    }

    private function uuidFromEmail(Email $email): UuidInterface
    {
        $uuid = $this->checkUserByEmail->existsEmail($email);

        if (!$uuid) {
            throw new InvalidCredentialsException();
        }

        return $uuid;
    }
}