<?php

declare(strict_types=1);


namespace App\User\Domain\ValueObject;


use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws \Assert\AssertionFailedException
     */
    public function invalid_email_should_throw_an_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Email::fromString('asd');
    }

    /**
     * @test
     *
     * @group unit
     */
    public function valid_email_should_be_converted_to_string(): void
    {
        $emailValue = 'valid@email.loc';
        $email = Email::fromString($emailValue);

        self::assertSame($emailValue, $email->toString());
        self::assertSame($emailValue, (string) $email);
    }
}