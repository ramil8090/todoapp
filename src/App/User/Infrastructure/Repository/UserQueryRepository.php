<?php

declare(strict_types=1);


namespace App\User\Infrastructure\Repository;


use App\Shared\Infrastructure\Exception\NotFoundException;
use App\User\Domain\User;
use App\User\Domain\ValueObject\Email;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class UserQueryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws NotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByEmailAsArray(Email $email): array
    {
        $userData = $this->getUserByEmailQueryBuilder($email)
            ->select('
                user.uuid,
                user.credentials.email,
                user.createdAt,
                user.updatedAt
            ')
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        if (!$userData) {
            throw new NotFoundException();
        }

        return $userData;
    }

    private function getUserByEmailQueryBuilder(Email $email): QueryBuilder
    {
        return $this->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter('email', $email->toString());
    }
}