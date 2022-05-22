<?php

declare(strict_types=1);


namespace App\TodoList\Domain\ValueObject;


use App\User\Domain\ValueObject\Email;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
final class Owner
{
    /**
     * @ORM\Column(type="email", name="owner_email")
     */
    public Email $email;

    /**
     * @param Email $email
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }


}