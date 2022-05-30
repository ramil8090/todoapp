<?php

declare(strict_types=1);


namespace App\TodoList\Infrastructure\Repository;


use App\Shared\Infrastructure\Persistence\Exception\NotFoundException;
use App\TodoList\Domain\Repository\TaskRepositoryInterface;
use App\TodoList\Domain\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

class TaskRepository extends ServiceEntityRepository implements TaskRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @throws NotFoundException
     */
    public function get(UuidInterface $uuid): Task
    {
        $task = $this->findOneBy(['uuid' => $uuid]);

        if (!$task) {
            throw new NotFoundException();
        }

        return $task;
    }

    public function store(Task $task): void
    {
        $this->getEntityManager()->persist($task);
    }
}