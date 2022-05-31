<?php

declare(strict_types=1);


namespace UI\Http\Rest\Controller\TodoList;


use App\Shared\Application\Command\CommandBusInterface;
use App\TodoList\Application\Command\ChangeTodoListTitle\ChangeTodoListTitleCommand;
use App\TodoList\Application\Command\CreateTodoList\CreateTodoListCommand;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Rest\Controller\CommandController;
use UI\Http\Rest\Response\OpenApi;
use UI\Http\Session;

class ChangeTodoListTitleController extends CommandController
{
    private Session $session;

    /**
     * @param Session $session
     * @param CommandBusInterface $commandBus
     */
    public function __construct(Session $session, CommandBusInterface $commandBus)
    {
        parent::__construct($commandBus);
        $this->session = $session;
    }

    /**
     * @Route(
     *     "/todolist/{uuid}/title",
     *     name="todolist_change_title",
     *     methods={"POST"}
     * )
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @throws AssertionFailedException
     */
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $title = $request->get('title');
        $ownerEmail = $this->session->get()->getUsername();

        Assertion::notNull($uuid);
        Assertion::notNull($title);
        Assertion::notNull($ownerEmail);

        $command = new ChangeTodoListTitleCommand($uuid, $title, $ownerEmail);

        $this->handle($command);

        return new JsonResponse();
    }
}