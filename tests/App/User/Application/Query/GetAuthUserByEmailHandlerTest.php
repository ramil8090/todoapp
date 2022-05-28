<?php

declare(strict_types=1);


namespace Tests\App\User\Application\Query;


use App\User\Application\Command\SignUp\SignUpCommand;
use App\User\Application\Query\Auth\GetAuthUserByEmail\GetAuthUserByEmailQuery;
use App\User\Infrastructure\Auth\Auth;
use Ramsey\Uuid\Uuid;
use Tests\App\Shared\Application\ApplicationTestCase;
use Throwable;

class GetAuthUserByEmailHandlerTest extends ApplicationTestCase
{
    const USER_EMAIL = 'user@email.com';
    const USER_PASS = 'password';

    /**
     * @test
     *
     * @group integration
     *
     * @throws Throwable
     */
    public function given_email_should_return_auth_object()
    {
        $result = $this->ask(new GetAuthUserByEmailQuery(self::USER_EMAIL));

        self::assertInstanceOf(Auth::class, $result);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->handle(new SignUpCommand(
            Uuid::uuid4()->toString(),
            self::USER_EMAIL,
            self::USER_PASS,
        ));
    }
}