<?php

declare(strict_types=1);


namespace App\User\Infrastructure\Repository;


use App\Shared\Infrastructure\Persistence\Exception\NotFoundException;
use App\User\Domain\Repository\CheckUserByEmailInterface;
use App\User\Domain\Repository\GetUserCredentialsByEmailInterface;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\User;
use App\User\Domain\ValueObject\Email;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface,
    CheckUserByEmailInterface, GetUserCredentialsByEmailInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function store(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws NotFoundException
     */
    public function get(UuidInterface $uuid): User
    {
        $user = $this->findOneBy(['uuid' => $uuid]);

        if (!$user) {
            throw new NotFoundException();
        }

        return $user;
    }

    public function existsEmail(Email $email): ?UuidInterface
    {
        $userData = $this->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter(':email', $email->toString())
            ->select(['user.uuid'])
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        return $userData['uuid'] ?? null;
    }

    /**
     * @throws NotFoundException
     */
    public function getCredentialsByEmail(Email $email): array
    {
        $userData = $this->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter(':email', $email->toString())
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        if (!$userData) {
            throw new NotFoundException();
        }

        return [
            $userData['uuid'],
            $userData['credentials.email'],
            $userData['credentials.hashedPassword'],
        ];
    }
}