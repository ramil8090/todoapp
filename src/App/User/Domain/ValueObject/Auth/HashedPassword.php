<?php

declare(strict_types=1);


namespace App\User\Domain\ValueObject\Auth;


use Assert\Assertion;
use Assert\AssertionFailedException;

final class HashedPassword
{
    private string $hashedPassword;

    public const COST = 12;

    private function __construct(string $hashedPassword)
    {
        $this->hashedPassword = $hashedPassword;
    }

    public function match(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedPassword);
    }

    /**
     * @throws AssertionFailedException
     */
    public static function encode(string $plainPassword): self
    {
        return new self(self::hash($plainPassword));
    }

    public static function fromHash(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }

    /**
     * @throws AssertionFailedException
     */
    private static function hash(string $plainPassword): string
    {
        Assertion::minLength($plainPassword, 6, 'Min 6 characters password');

        return password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => self::COST]);
    }

    public function toString(): string
    {
        return $this->hashedPassword;
    }

    public function __toString(): string
    {
        return $this->hashedPassword;
    }
}