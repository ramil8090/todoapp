<?php

declare(strict_types=1);


namespace App\User\Application\Query\FindByEmail;


use App\Shared\Application\Query\Item;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Infrastructure\Exception\NotFoundException;
use App\User\Infrastructure\Repository\UserQueryRepository;
use Doctrine\ORM\NonUniqueResultException;

class FindByEmailHandler implements QueryHandlerInterface
{
    private UserQueryRepository $userQueryRepository;

    public function __construct(UserQueryRepository $userQueryRepository)
    {
        $this->userQueryRepository = $userQueryRepository;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NotFoundException
     */
    public function __invoke(FindByEmailQuery $query)
    {
        $userData = $this->userQueryRepository->oneByEmailAsArray($query->email);

        return Item::fromPayload($userData['uuid']->toString(), 'UserView', $userData);
    }
}