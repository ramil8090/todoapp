<?php

declare(strict_types=1);


namespace App\TodoList\Infrastructure\User\Adapter;


use App\Shared\Infrastructure\Persistence\Exception\NotFoundException;
use App\TodoList\Domain\ValueObject\Owner;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Repository\UserQueryRepository;

class DoctrineUserToOwnerAdapter implements UserToOwnerAdapterInterface
{
    private UserQueryRepository $userQueryRepository;

    /**
     * @param UserQueryRepository $userQueryRepository
     */
    public function __construct(UserQueryRepository $userQueryRepository)
    {
        $this->userQueryRepository = $userQueryRepository;
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function toOwner(Email $email): ?Owner
    {
        try {
            $user = $this->userQueryRepository->oneByEmailAsArray($email);
            return new Owner($user['credentials.email']);
        } catch (NotFoundException $e) {
            return null;
        }
    }
}