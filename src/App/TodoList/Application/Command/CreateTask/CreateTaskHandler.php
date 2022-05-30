<?php

declare(strict_types=1);


namespace App\TodoList\Application\Command\CreateTask;


use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Infrastructure\Event\Publisher\EventDispatcherInterface;
use App\TodoList\Domain\Repository\TaskRepositoryInterface;
use App\TodoList\Domain\Specification\TodoListExistSpecification;
use App\TodoList\Domain\Task;

class CreateTaskHandler implements CommandHandlerInterface
{
    private TaskRepositoryInterface $taskRepository;
    private TodoListExistSpecification $todoListExistSpecification;
    private EventDispatcherInterface $eventDispatcher;

    /**
     * @param TaskRepositoryInterface $taskRepository
     * @param TodoListExistSpecification $specification
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        TodoListExistSpecification $specification,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->taskRepository = $taskRepository;
        $this->todoListExistSpecification = $specification;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws DateTimeException
     */
    public function __invoke(CreateTaskCommand $command)
    {
        $task = new Task(
            $command->uuid,
            $command->todoListUuid,
            $command->title,
            $command->description,
            $this->todoListExistSpecification
        );

        $this->taskRepository->store($task);

        $this->eventDispatcher->dispatch($task->releaseEvents());
    }
}