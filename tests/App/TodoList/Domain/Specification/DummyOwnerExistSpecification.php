<?php

declare(strict_types=1);


namespace Tests\App\TodoList\Domain\Specification;


use App\TodoList\Domain\Exception\OwnerNotExistException;
use App\TodoList\Domain\Specification\OwnerExistSpecification;
use App\User\Domain\ValueObject\Email;

class DummyOwnerExistSpecification implements OwnerExistSpecification
{
    private bool $isExist;

    public function __construct(bool $isExist = true)
    {
        $this->isExist = $isExist;
    }

    public function isExist(Email $email): bool
    {
        if (!$this->isExist) {
            throw new OwnerNotExistException();
        }

        return false;
    }
}