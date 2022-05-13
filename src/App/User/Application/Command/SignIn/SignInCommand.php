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
     * @param Email $email
     * @param string $plainPassword
     */
    public function __construct(Email $email, string $plainPassword)
    {
        $this->email = $email;
        $this->plainPassword = $plainPassword;
    }


}