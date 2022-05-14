<?php

declare(strict_types=1);


namespace UI\Http\Rest\Controller\Auth;


use App\User\Application\Command\SignIn\SignInCommand;
use App\User\Application\Query\Auth\GetToken\GetTokenQuery;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Rest\Controller\CommandQueryController;
use UI\Http\Rest\Response\OpenApi;

class CheckController extends CommandQueryController
{
    /**
     * @Route(
     *     "/auth_check",
     *     name="auth_check",
     *     methods={"POST"},
     *     requirements={
     *      "_username": "\w+",
     *      "_password": "\w+"
     *     }
     * )
     * @param Request $request
     * @return OpenApi
     * @throws AssertionFailedException
     */
    public function __invoke(Request $request): OpenApi
    {
        $username = $request->get('_username');
        $password = $request->get('_password');

        Assertion::notNull($username, "Username can't be empty");

        $command = new SignInCommand(
            $username,
            $password
        );

        $this->handle($command);

        return OpenApi::fromPayload(
            [
                'token' => $this->ask(new GetTokenQuery($username))
            ],
            OpenApi::HTTP_OK
        );
    }
}