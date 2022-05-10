<?php

declare(strict_types=1);


namespace App\User\Domain\ValueObject\Auth;


use App\User\Domain\ValueObject\Email;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Credentials
{
    /**
     * @ORM\Column(type="email")
     */
    public Email $email;

    /**
     * @ORM\Column(type="hashed_password")
     */
    public HashedPassword $hashedPassword;

    /**
     * @param Email $email
     * @param HashedPassword $hashedPassword
     */
    public function __construct(Email $email, HashedPassword $hashedPassword)
    {
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
    }
}