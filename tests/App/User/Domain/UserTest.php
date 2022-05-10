<?php

declare(strict_types=1);


namespace App\User\Domain;


use App\User\Domain\Exception\EmailAlreadyExistException;
use App\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\User\Domain\ValueObject\Auth\Credentials;
use App\User\Domain\ValueObject\Auth\HashedPassword;
use App\User\Domain\ValueObject\Email;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UserTest extends TestCase implements UniqueEmailSpecificationInterface
{
    private bool $isUniqueException = false;

    public function isUnique(Email $email): bool
    {
        if ($this->isUniqueException) {
            throw new EmailAlreadyExistException();
        }

        return true;
    }

    /**
     * @test
     *
     * @group unit
     */
    public function given_a_valid_email_it_should_create_a_user_instance()
    {
        $emailValue = "some@email.loc";

        $user = User::create(
            Uuid::uuid4(),
            new Credentials(
                Email::fromString($emailValue),
                HashedPassword::encode('password')
            ),
            $this
        );

        self::assertSame($emailValue, $user->email());
        self::assertNotEmpty($user->uuid());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws AssertionFailedException
     */
    public function given_new_email_it_should_change_if_not_eq_to_prev(): void
    {
        $emailValue = 'some@email.loc';

        $user = User::create(
            Uuid::uuid4(),
            new Credentials(
                Email::fromString($emailValue),
                HashedPassword::encode('password')
            ),
            $this
        );

        $newEmailValue = 'new@email.loc';

        $user->changeEmail(Email::fromString($newEmailValue), $this);

        self::assertSame($newEmailValue, $user->email(), 'Emails should be equals');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws AssertionFailedException
     */
    public function given_a_registered_email_should_throw_an_exception(): void
    {
        self::expectException(EmailAlreadyExistException::class);

        $this->isUniqueException = true;

        $emailValue = 'some@email.loc';

        User::create(
            Uuid::uuid4(),
            new Credentials(
                Email::fromString($emailValue),
                HashedPassword::encode('password')
            ),
            $this
        );
    }
}