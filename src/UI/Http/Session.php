<?php

declare(strict_types=1);


namespace UI\Http;


use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Infrastructure\Auth\Auth;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class Session
{
    private TokenStorageInterface $storage;

    /**
     * @param TokenStorageInterface $storage
     */
    public function __construct(TokenStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function get(): Auth
    {
        $token = $this->storage->getToken();

        if (!$token) {
            throw new InvalidCredentialsException();
        }

        /** @var Auth $user */
        $user = $token->getUser();

        if (!$user) {
            throw new InvalidCredentialsException();
        }

        return $user;
    }
}