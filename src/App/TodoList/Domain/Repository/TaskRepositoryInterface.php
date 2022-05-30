<?php

declare(strict_types=1);


namespace App\TodoList\Domain\Repository;


use App\Shared\Infrastructure\Persistence\Exception\NotFoundException;
use App\TodoList\Domain\Task;
use Ramsey\Uuid\UuidInterface;

interface TaskRepositoryInterface
{
    /**
     * @param UuidInterface $uuid
     * @return Task
     * @throws NotFoundException
     */
    public function get(UuidInterface $uuid): Task;

    public function store(Task $task): void;
}