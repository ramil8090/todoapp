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
use OpenApi\Annotations as OA;

class SignUpController extends CommandController
{
    /**
     * @Route(
     *     "/signup",
     *     name="user_create",
     *     methods={"POST"}
     * )
     *
     * @OA\Response(
     *     response=201,
     *     description="User created successfully"
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @OA\Response(
     *     response=409,
     *     description="Conflict"
     * )
     * @OA\RequestBody(
     *     @OA\Schema(type="object"),
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="uuid", type="string"),
     *         @OA\Property(property="email", type="string"),
     *         @OA\Property(property="password", type="string")
     *     )
     * )
     *
     * @OA\Tag(name="User")
     *
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