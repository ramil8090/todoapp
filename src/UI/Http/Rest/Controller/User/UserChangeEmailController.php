<?php

declare(strict_types=1);


namespace UI\Http\Rest\Controller\User;


use App\Shared\Application\Command\CommandBusInterface;
use App\User\Application\Command\ChangeEmail\ChangeEmailCommand;
use App\User\Domain\Exception\ForbiddenException;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Ramsey\Uuid\Nonstandard\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Rest\Controller\CommandController;
use UI\Http\Session;

class UserChangeEmailController extends CommandController
{
    private Session $session;

    /**
     * @param Session $session
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
     * @param string $uuid
     * @param Request $request
     * @return JsonResponse
     * @throws AssertionFailedException
     */
    public function __invoke(string $uuid, Request $request)
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