<?php

declare(strict_types=1);


namespace App\TodoList\Domain\Repository;


use App\TodoList\Domain\TodoList;
use Ramsey\Uuid\Uuid;

interface TodoListRepositoryInterface
{
    public function get(Uuid $uuid): TodoList;

    public function store(TodoList $todoList): void;
}