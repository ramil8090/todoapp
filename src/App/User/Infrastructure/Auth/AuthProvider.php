<?php

declare(strict_types=1);


namespace App\User\Infrastructure\Auth;


use App\Shared\Infrastructure\Persistence\Exception\NotFoundException;
use App\User\Domain\Repository\GetUserCredentialsByEmailInterface;
use App\User\Domain\ValueObject\Email;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class AuthProvider implements UserProviderInterface
{
    private GetUserCredentialsByEmailInterface $userStore;

    public function __construct(GetUserCredentialsByEmailInterface $userStore)
    {
        $this->userStore = $userStore;
    }

    public function refreshUser(UserInterface $user)
    {
        [$uuid, $email, $hashedPassword] = $this->userStore->getCredentialsByEmail(
            Email::fromString($user->getUserIdentifier())
        );

        return Auth::create($uuid, $email, $hashedPassword);
    }

    public function supportsClass(string $class)
    {
        return Auth::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        try {
            [$uuid, $email, $hashedPassword] = $this->userStore->getCredentialsByEmail(
                Email::fromString($identifier)
            );

            return Auth::create($uuid, $email, $hashedPassword);
        } catch (NotFoundException $e) {
            throw new UserNotFoundException();
        }
    }
}