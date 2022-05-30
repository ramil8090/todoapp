<?php

declare(strict_types=1);


namespace App\TodoList\Domain\Specification;


use App\TodoList\Domain\Exception\NotOwnerException;
use App\User\Domain\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

interface TodoListOwnerSpecification
{
    /**
     * @param UuidInterface $todoListUuid
     * @param Email $userEmail
     * @return bool
     * @throws NotOwnerException
     */
    public function isOwner(UuidInterface $todoListUuid, Email $userEmail): bool;
}