<?php

declare(strict_types=1);


namespace App\TodoList\Domain\Exception;


use Throwable;

class OwnerNotExistException extends \InvalidArgumentException implements \Throwable
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct('Owner is not exist');
    }
}