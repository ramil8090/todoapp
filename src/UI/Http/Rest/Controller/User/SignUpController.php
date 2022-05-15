<?php

declare(strict_types=1);


namespace UI\Http\Rest\Controller\User;


use App\User\Application\Command\SignUp\SignUpCommand;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Rest\Controller\CommandController;
use UI\Http\Rest\Response\OpenApi;

class SignUpController extends CommandController
{
    /**
     * @Route(
     *     "/signup",
     *     name="user_create",
     *     methods={"POST"}
     * )
     * @throws AssertionFailedException
     */
    public function __invoke(Request $request): OpenApi
    {
        $uuid = $request->get('uuid');
        $email = $request->get('email');
        $plainPassword = $request->get('password');

        Assertion::notNull($uuid, "Uuid can't be null");
        Assertion::notNull($email, "Email can't be null");
        Assertion::notNull($plainPassword, "Password can't be null");

        $command = new SignUpCommand($uuid, $email, $plainPassword);

        $this->handle($command);

        return OpenApi::created("/user/$email");
    }
}