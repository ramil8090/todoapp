<?php

declare(strict_types=1);


namespace App\TodoList\Infrastructure\Specification;


use App\Shared\Infrastructure\Persistence\Exception\NotFoundException;
use App\TodoList\Domain\Exception\TodoListNotExistException;
use App\TodoList\Domain\Repository\TodoListRepositoryInterface;
use App\TodoList\Domain\Specification\TodoListExistSpecification;
use Ramsey\Uuid\UuidInterface;

class DoctrineTodoListExistSpecification implements TodoListExistSpecification
{
    private TodoListRepositoryInterface $todoListRepository;

    /**
     * @param TodoListRepositoryInterface $todoListRepository
     */
    public function __construct(TodoListRepositoryInterface $todoListRepository)
    {
        $this->todoListRepository = $todoListRepository;
    }

    public function isExist(UuidInterface $todoListUuid): bool
    {
        try {
            $this->todoListRepository->get($todoListUuid);
        } catch (NotFoundException $e) {
            throw new TodoListNotExistException();
        }
        return true;
    }
}