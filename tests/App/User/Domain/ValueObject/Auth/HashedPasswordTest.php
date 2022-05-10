<?php

declare(strict_types=1);


namespace App\User\Domain\ValueObject\Auth;


use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class HashedPasswordTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws AssertionFailedException
     */
    public function encoded_password_should_be_validated(): void
    {
        $pass = HashedPassword::encode('0123456789');

        self::assertTrue($pass->match('0123456789'));
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws AssertionFailedException
     */
    public function min_length_is_6_characters(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        HashedPassword::encode('12345');
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws AssertionFailedException
     */
    public function from_hash_password_should_still_be_valid(): void
    {
        $pass = HashedPassword::fromHash((string) HashedPassword::encode('0123456789'));

        self::assertTrue($pass->match('0123456789'));
    }
}