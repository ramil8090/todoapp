<?php

declare(strict_types=1);


namespace App\User\Application\Command\SignIn;


use App\Shared\Application\Command\CommandInterface;
use App\User\Domain\ValueObject\Email;

class SignInCommand implements CommandInterface
{
    public Email $email;

    public string $plainPassword;

    /**
     * @param string $email
     * @param string $plainPassword
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $email, string $plainPassword)
    {
        $this->email = Email::fromString($email);
        $this->plainPassword = $plainPassword;
    }


}