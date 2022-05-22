<?php

declare(strict_types=1);


namespace App\TodoList\Infrastructure\User\Adapter;


use App\TodoList\Domain\ValueObject\Owner;
use App\User\Domain\ValueObject\Email;

interface UserToOwnerAdapterInterface
{
    public function toOwner(Email $email): ?Owner;
}