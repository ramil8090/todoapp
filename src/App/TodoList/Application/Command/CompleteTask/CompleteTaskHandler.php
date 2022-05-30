<?php

declare(strict_types=1);


namespace App\TodoList\Application\Command\CompleteTask;


use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Infrastructure\Event\Publisher\EventDispatcherInterface;
use App\Shared\Infrastructure\Persistence\Exception\NotFoundException;
use App\TodoList\Domain\Repository\TaskRepositoryInterface;
use App\TodoList\Domain\Specification\TodoListOwnerSpecification;

class CompleteTaskHandler implements CommandHandlerInterface
{
    private TaskRepositoryInterface $taskRepository;
    private TodoListOwnerSpecification $todoListOwnerSpecification;
    private EventDispatcherInterface $eventDispatcher;

    /**
     * @param TaskRepositoryInterface $taskRepository
     * @param TodoListOwnerSpecification $todoListOwnerSpecification
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        TodoListOwnerSpecification $todoListOwnerSpecification,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->taskRepository = $taskRepository;
        $this->todoListOwnerSpecification = $todoListOwnerSpecification;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws NotFoundException
     */
    public function __invoke(CompleteTaskCommand $command)
    {
        $task = $this->taskRepository->get($command->uuid);

        $this->todoListOwnerSpecification->isOwner($task->todoListUuid(), $command->userEmail);

        $task->setCompleted();

        $this->taskRepository->store($task);

        $this->eventDispatcher->dispatch($task->releaseEvents());
    }
}