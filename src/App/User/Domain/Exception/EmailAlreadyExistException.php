<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use Throwable;

class EmailAlreadyExistException extends \InvalidArgumentException implements \Throwable
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct('Email is already registered');
    }
}