<?php

declare(strict_types=1);


namespace UI\Http\Rest\Controller\User;


use App\Shared\Application\Command\CommandBusInterface;
use App\User\Application\Command\ChangeEmail\ChangeEmailCommand;
use App\User\Domain\Exception\ForbiddenException;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Nonstandard\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Rest\Controller\CommandController;
use UI\Http\Session;
use OpenApi\Annotations as OA;

class UserChangeEmailController extends CommandController
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
     *     "/users/{uuid}/email",
     *     name="user_change_email",
     *     methods={"POST"}
     * )
     *
     * @OA\Response(
     *     response=201,
     *     description="Email changed"
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @OA\Response(
     *     response=409,
     *     description="Conflict"
     * )
     *
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="email", type="string"),
     *     )
     * )
     *
     * @OA\Parameter(
     *     name="uuid",
     *     in="path",
     *     @OA\Schema(type="string")
     * )
     *
     * @OA\Tag(name="User")
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @throws AssertionFailedException
     */
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $this->validateUuid($uuid);

        $email = $request->get('email');

        Assertion::notNull($email, "Email can't be null");

        $command = new ChangeEmailCommand($uuid, $email);

        $this->handle($command);

        return new JsonResponse();
    }

    private function validateUuid(string $uuid): void
    {
        if (!$this->session->get()->uuid()->equals(Uuid::fromString($uuid))) {
            throw new ForbiddenException();
        }
    }
}