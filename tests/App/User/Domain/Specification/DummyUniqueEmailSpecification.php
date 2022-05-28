<?php

declare(strict_types=1);


namespace Tests\App\User\Domain\Specification;


use App\User\Domain\Exception\EmailAlreadyExistException;
use App\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\User\Domain\ValueObject\Email;

class DummyUniqueEmailSpecification implements UniqueEmailSpecificationInterface
{
    private bool $isUniqueException;

    public function __construct(bool $isUniqueException = false)
    {
        $this->isUniqueException = $isUniqueException;
    }

    public function isUnique(Email $email): bool
    {
        if ($this->isUniqueException) {
            throw new EmailAlreadyExistException();
        }

        return true;
    }
}