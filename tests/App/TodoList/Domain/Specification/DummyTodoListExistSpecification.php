<?php

declare(strict_types=1);


namespace Tests\App\TodoList\Domain\Specification;


use App\TodoList\Domain\Exception\TodoListNotExistException;
use App\TodoList\Domain\Specification\TodoListExistSpecification;
use Ramsey\Uuid\UuidInterface;

class DummyTodoListExistSpecification implements TodoListExistSpecification
{
    private bool $isExist;

    public function __construct(bool $isExist = true)
    {
        $this->isExist = $isExist;
    }

    public function isExist(UuidInterface $todoListUuid): bool
    {
        if (!$this->isExist) {
            throw new TodoListNotExistException();
        }

        return true;
    }
}