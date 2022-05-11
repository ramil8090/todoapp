<?php

declare(strict_types=1);


namespace App\User\Application\Query\FindByEmail;


use App\Shared\Application\Query\QueryInterface;
use App\User\Domain\ValueObject\Email;

class FindByEmailQuery implements QueryInterface
{
    public Email $email;

    public function __construct(string $email)
    {
        $this->email = Email::fromString($email);
    }
}