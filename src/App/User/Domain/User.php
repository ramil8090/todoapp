<?php

declare(strict_types=1);


namespace App\User\Domain;


use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Domain\ValueObject\DateTime;
use App\User\Domain\Event\UserSignedIn;
use App\User\Domain\Event\UserWasCreated;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\User\Domain\ValueObject\Auth\Credentials;
use App\User\Domain\ValueObject\Auth\HashedPassword;
use App\User\Domain\ValueObject\Email;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
final class User extends AggregateRoot
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid_binary", unique=true)
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Embedded(class=Credentials::class, columnPrefix=false)
     */
    private Credentials $credentials;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $updatedAt;

    /**
     * @throws DateTimeException
     */
    public static function create(
        UuidInterface $uuid,
        Credentials $credentials,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): self
    {
        $uniqueEmailSpecification->isUnique($credentials->email);

        $user = new self();

        $user->uuid = $uuid;
        $user->credentials = $credentials;
        $user->createdAt = DateTime::now();

        $user->apply(new UserWasCreated($uuid, $credentials, DateTime::now()));

        return $user;
    }

    public function changeEmail(
        Email $email,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ): void
    {
        $uniqueEmailSpecification->isUnique($email);

        $this->credentials->email = $email;
    }

    public function signIn(string $plainPassword): void
    {
        if (!$this->credentials->hashedPassword->match($plainPassword)) {
            throw new InvalidCredentialsException();
        }

        $this->apply(new UserSignedIn($this->uuid, $this->credentials->email));
    }

    /**
     * @param Email $email
     */
    private function setEmail(Email $email): void
    {
        $this->credentials->email = $email;
    }

    /**
     * @param HashedPassword $hashedPassword
     */
    private function setHashedPassword(HashedPassword $hashedPassword): void
    {
        $this->credentials->hashedPassword = $hashedPassword;
    }

    /**
     * @param DateTime|null $createdAt
     */
    private function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param DateTime|null $updatedAt
     */
    private function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function uuid(): string
    {
        return $this->uuid->toString();
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->credentials->email->toString();
    }

    /**
     * @return string
     */
    public function hashedPassword(): string
    {
        return $this->credentials->hashedPassword->toString();
    }

    /**
     * @return DateTime|null
     */
    public function createdAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function updatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

}