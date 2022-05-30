<?php

declare(strict_types=1);


namespace App\TodoList\Domain\Specification;


use App\TodoList\Domain\Exception\TodoListNotExistException;
use Ramsey\Uuid\UuidInterface;

interface TodoListExistSpecification
{
    /**
     * @param UuidInterface $todoListUuid
     * @return bool
     * @throws TodoListNotExistException
     */
    public function isExist(UuidInterface $todoListUuid): bool;
}