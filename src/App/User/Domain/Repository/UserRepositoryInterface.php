<?php

declare(strict_types=1);


namespace App\User\Domain\Repository;


use App\User\Domain\User;
use Ramsey\Uuid\UuidInterface;

interface UserRepositoryInterface
{
    public function store(User $user): void;

    public function get(UuidInterface $uuid): User;
}