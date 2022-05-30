<?php

declare(strict_types=1);


namespace App\TodoList\Domain\Repository;


use App\TodoList\Domain\Task;
use Ramsey\Uuid\Uuid;

interface TaskRepositoryInterface
{
    public function get(Uuid $uuid): Task;

    public function store(Task $task): void;
}