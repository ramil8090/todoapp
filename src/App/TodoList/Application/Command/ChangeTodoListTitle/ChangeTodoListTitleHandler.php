<?php

declare(strict_types=1);


namespace App\TodoList\Application\Command\ChangeTodoListTitle;


use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Infrastructure\Event\Publisher\EventDispatcherInterface;
use App\Shared\Infrastructure\Persistence\Exception\NotFoundException;
use App\TodoList\Domain\Exception\NotOwnerException;
use App\TodoList\Domain\Repository\TodoListRepositoryInterface;
use App\TodoList\Domain\Specification\TodoListOwnerSpecification;

class ChangeTodoListTitleHandler implements CommandHandlerInterface
{
    private TodoListRepositoryInterface $todoListRepository;
    private TodoListOwnerSpecification $todoListOwnerSpecification;
    private EventDispatcherInterface $eventDispatcher;

    /**
     * @param TodoListRepositoryInterface $todoListRepository
     * @param TodoListOwnerSpecification $todoListOwnerSpecification
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TodoListRepositoryInterface $todoListRepository,
        TodoListOwnerSpecification $todoListOwnerSpecification,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->todoListRepository = $todoListRepository;
        $this->todoListOwnerSpecification = $todoListOwnerSpecification;
        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     * @throws NotFoundException
     * @throws NotOwnerException
     * @throws DateTimeException
     */
    public function __invoke(ChangeTodoListTitleCommand $command)
    {
        $todoList = $this->todoListRepository->get($command->uuid);

        $this->todoListOwnerSpecification->isOwner($command->uuid, $command->userEmail);

        $todoList->changeTitle($command->title);

        $this->eventDispatcher->dispatch($todoList->releaseEvents());
    }
}