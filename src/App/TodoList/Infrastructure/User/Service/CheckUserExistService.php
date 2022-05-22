<?php

declare(strict_types=1);


namespace App\TodoList\Infrastructure\User\Service;


use App\TodoList\Domain\Exception\OwnerNotExistException;
use App\TodoList\Domain\Specification\OwnerExistSpecification;
use App\TodoList\Infrastructure\User\Adapter\UserToOwnerAdapterInterface;
use App\User\Domain\ValueObject\Email;

class CheckUserExistService implements OwnerExistSpecification
{
    private UserToOwnerAdapterInterface $adapter;

    /**
     * @param UserToOwnerAdapterInterface $adapter
     */
    public function __construct(UserToOwnerAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function isExist(Email $email): bool
    {
        $owner = $this->adapter->toOwner($email);
        if (!$owner) {
            throw new OwnerNotExistException();
        }
        return true;
    }
}