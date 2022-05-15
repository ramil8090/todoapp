<?php

declare(strict_types=1);


namespace UI\Http\Rest\Controller\User;


use App\Shared\Application\Query\Item;
use App\User\Application\Query\FindByEmail\FindByEmailQuery;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Rest\Controller\QueryController;
use UI\Http\Rest\Response\OpenApi;
use OpenApi\Annotations as OA;

class GetUserByEmailController extends QueryController
{
    /**
     * @Route(
     *     "/user/{email}",
     *     name="find_user",
     *     methods={"GET"}
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns the user of the given email",
     *     ref="#/components/responses/users"
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad request",
     *     @OA\JsonContent(ref="#/components/schemas/Error")
     * )
     * @OA\Response(
     *     response=404,
     *     description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/Error")
     * )
     *
     * @OA\RequestBody(@OA\MediaType(mediaType="application/json"))
     *
     * @OA\Parameter(
     *     name="email",
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
    public function __invoke(string $email): OpenApi
    {
        Assertion::email($email, "Email can't be empty or invalid");

        $query = new FindByEmailQuery($email);

        /** @var Item $user */
        $user = $this->ask($query);

        return $this->json($user);
    }
}