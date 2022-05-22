<?php

declare(strict_types=1);


namespace App\TodoList\Domain\ValueObject;


use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class OwnerTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     */
    public function given_an_email_should_create_an_owner()
    {
        $ownerEmail = "user@email.loc";

        $owner = new Owner(Email::fromString($ownerEmail));

        self::assertSame($ownerEmail, $owner->email->toString());
    }
}