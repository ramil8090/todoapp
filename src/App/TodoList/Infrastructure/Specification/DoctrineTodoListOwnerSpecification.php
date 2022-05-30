<?php

declare(strict_types=1);


namespace App\TodoList\Infrastructure\Specification;


use App\Shared\Infrastructure\Persistence\Exception\NotFoundException;
use App\TodoList\Domain\Exception\NotOwnerException;
use App\TodoList\Domain\Repository\TodoListRepositoryInterface;
use App\TodoList\Domain\Specification\TodoListOwnerSpecification;
use App\User\Domain\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

class DoctrineTodoListOwnerSpecification implements TodoListOwnerSpecification
{
    private TodoListRepositoryInterface $todoListRepository;

    /**
     * @param TodoListRepositoryInterface $todoListRepository
     */
    public function __construct(TodoListRepositoryInterface $todoListRepository)
    {
        $this->todoListRepository = $todoListRepository;
    }


    /**
     * @throws NotFoundException
     */
    public function isOwner(UuidInterface $todoListUuid, Email $userEmail): bool
    {
        $todoList = $this->todoListRepository->get($todoListUuid);
        if ($todoList->ownerEmail() !== $userEmail->toString()) {
            throw new NotOwnerException();
        }
        return true;
    }
}