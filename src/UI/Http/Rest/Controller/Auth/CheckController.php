<?php

declare(strict_types=1);


namespace UI\Http\Rest\Controller\Auth;


use App\User\Application\Command\SignIn\SignInCommand;
use App\User\Application\Query\Auth\GetToken\GetTokenQuery;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;
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
     * @OA\Response(
     *     response=200,
     *     description="Login success",
     *     @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="token", type="string")
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad request",
     *     @OA\JsonContent(ref="#/components/schemas/Error")
     * )
     * @OA\Response(
     *     response=401,
     *     description="Bad credentials",
     *     @OA\JsonContent(ref="#/components/schemas/Error")
     * )
     *
     * @OA\Tag(name="Auth")
     *
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