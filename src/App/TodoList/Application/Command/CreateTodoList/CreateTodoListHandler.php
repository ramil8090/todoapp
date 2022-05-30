<?php

declare(strict_types=1);


namespace App\TodoList\Application\Command\CreateTodoList;


use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Infrastructure\Event\Publisher\EventDispatcherInterface;
use App\TodoList\Domain\Repository\TodoListRepositoryInterface;
use App\TodoList\Domain\Specification\OwnerExistSpecification;
use App\TodoList\Domain\TodoList;

class CreateTodoListHandler implements CommandHandlerInterface
{
    private TodoListRepositoryInterface $todoListRepository;

    private OwnerExistSpecification $ownerExistSpecification;

    private EventDispatcherInterface $eventDispatcher;

    /**
     * @param TodoListRepositoryInterface $todoListRepository
     * @param OwnerExistSpecification $ownerExistSpecification
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(TodoListRepositoryInterface $todoListRepository, OwnerExistSpecification $ownerExistSpecification, EventDispatcherInterface $eventDispatcher)
    {
        $this->todoListRepository = $todoListRepository;
        $this->ownerExistSpecification = $ownerExistSpecification;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(CreateTodoListCommand $command)
    {
        $todoList = TodoList::create(
            $command->uuid,
            $command->owner,
            $command->title,
            $this->ownerExistSpecification
        );

        $this->todoListRepository->store($todoList);

        $this->eventDispatcher->dispatch($todoList->releaseEvents());
    }
}