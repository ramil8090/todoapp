<?php

declare(strict_types=1);


namespace App\TodoList\Domain\Specification;


use App\TodoList\Domain\Exception\OwnerNotExistException;
use App\User\Domain\ValueObject\Email;

interface OwnerExistSpecification
{
    /**
     * @param Email $email
     * @return bool
     * @throws OwnerNotExistException
     */
    public function isExist(Email $email): bool;
}