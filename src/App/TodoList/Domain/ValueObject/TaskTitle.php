<?php

declare(strict_types=1);


namespace App\TodoList\Domain\ValueObject;


use Assert\Assertion;
use Assert\AssertionFailedException;

class TaskTitle
{
    private string $title;

    /**
     * @param $title
     */
    private function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * @throws AssertionFailedException
     */
    public static function fromString(string $title): self
    {
        Assertion::notEmpty($title, 'Task title can\'t be empty');

        return new self($title);
    }

    public function toString(): string
    {
        return $this->title;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}