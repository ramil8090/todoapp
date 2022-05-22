<?php

declare(strict_types=1);


namespace UI\Http\Rest\Controller\TodoList;


use App\TodoList\Application\Command\CreateTodoList\CreateTodoListCommand;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Rest\Controller\CommandController;
use UI\Http\Rest\Response\OpenApi;

class CreateTodoListController extends CommandController
{
    /**
     * @Route(
     *     "/todolist",
     *     name="todolist_create",
     *     methods={"POSt"}
     * )
     * @throws AssertionFailedException
     */
    public function __invoke(Request $request)
    {
        $uuid = $request->get('uuid');
        $title = $request->get('title');
        $ownerEmail = $request->get('ownerEmail');

        Assertion::notNull($uuid);
        Assertion::notNull($title);
        Assertion::notNull($ownerEmail);

        $command = new CreateTodoListCommand($uuid, $title, $ownerEmail);

        $this->handle($command);

        return OpenApi::created("/todolist/$uuid");
    }
}