<?php

declare(strict_types=1);


namespace App\TodoList\Domain\Repository;


use App\Shared\Infrastructure\Persistence\Exception\NotFoundException;
use App\TodoList\Domain\TodoList;
use Ramsey\Uuid\UuidInterface;

interface TodoListRepositoryInterface
{
    /**
     * @param UuidInterface $uuid
     * @return TodoList
     * @throws NotFoundException
     */
    public function get(UuidInterface $uuid): TodoList;

    /**
     * @param TodoList $todoList
     */
    public function store(TodoList $todoList): void;
}