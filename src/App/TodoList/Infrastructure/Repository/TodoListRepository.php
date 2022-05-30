<?php

declare(strict_types=1);


namespace App\TodoList\Infrastructure\Repository;


use App\Shared\Infrastructure\Persistence\Exception\NotFoundException;
use App\TodoList\Domain\Repository\TodoListRepositoryInterface;
use App\TodoList\Domain\TodoList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

class TodoListRepository extends ServiceEntityRepository implements TodoListRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TodoList::class);
    }

    /**
     * @throws NotFoundException
     */
    public function get(UuidInterface $uuid): TodoList
    {
        $todoList = $this->findOneBy(['uuid' => $uuid]);

        if (!$todoList) {
            throw new NotFoundException();
        }

        return $todoList;
    }

    public function store(TodoList $todoList): void
    {
        $this->getEntityManager()->persist($todoList);
    }
}