<?php

declare(strict_types=1);


namespace Tests\App\User\Application\Query;


use App\Shared\Application\Query\Item;
use App\User\Application\Command\SignUp\SignUpCommand;
use App\User\Application\Query\FindByEmail\FindByEmailQuery;
use Ramsey\Uuid\Uuid;
use Tests\App\Shared\Application\ApplicationTestCase;

class FindByEmailHandlerTest extends ApplicationTestCase
{
    const USER_EMAIL = 'user@email.com';
    const USER_PASS = 'password';

    /**
     * @test
     *
     * @group integration
     */
    public function given_email_should_return_user_data()
    {
        $result = $this->ask(new FindByEmailQuery(self::USER_EMAIL));

        self::assertInstanceOf(Item::class, $result);
        self::assertSame('User', $result->type);
        self::assertSame(self::USER_EMAIL, $result->resource['credentials.email']->toString());
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