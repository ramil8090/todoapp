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
use Tests\App\User\Domain\Specification\DummyUniqueEmailSpecification;

class UserTest extends TestCase
{

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
            new DummyUniqueEmailSpecification()
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
        $uniqueEmailSpecification = new DummyUniqueEmailSpecification();

        $user = User::create(
            Uuid::uuid4(),
            new Credentials(
                Email::fromString($emailValue),
                HashedPassword::encode('password')
            ),
            $uniqueEmailSpecification
        );

        $newEmailValue = 'new@email.loc';

        $user->changeEmail(Email::fromString($newEmailValue), $uniqueEmailSpecification);

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

        $emailValue = 'some@email.loc';

        User::create(
            Uuid::uuid4(),
            new Credentials(
                Email::fromString($emailValue),
                HashedPassword::encode('password')
            ),
            new DummyUniqueEmailSpecification(true)
        );
    }
}