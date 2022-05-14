<?php

declare(strict_types=1);


namespace UI\Http\Rest\Controller\User;


use App\Shared\Application\Query\Item;
use App\User\Application\Query\FindByEmail\FindByEmailQuery;
use Assert\Assertion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UI\Http\Rest\Controller\QueryController;

class GetUserByEmailController extends QueryController
{
    /**
     * @Route(
     *     "/user/{email}",
     *     name="find_user",
     *     methods={"GET"}
     * )
     * @Security("is_granted('ROLE_USER')")
     *
     * @param string $email
     * @return \UI\Http\Rest\Response\OpenApi
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(string $email)
    {
        Assertion::email($email, "Email can't be empty or invalid");

        $query = new FindByEmailQuery($email);

        /** @var Item $user */
        $user = $this->ask($query);

        return $this->json($user);
    }
}