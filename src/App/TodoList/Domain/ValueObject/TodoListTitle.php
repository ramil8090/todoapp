<?php

declare(strict_types=1);


namespace App\TodoList\Domain\ValueObject;


use Assert\Assertion;
use Assert\AssertionFailedException;

class TodoListTitle implements \JsonSerializable
{
    public string $title;

    /**
     * @param string $title
     * @throws AssertionFailedException
     */
    public function __construct(string $title)
    {
        Assertion::notBlank($title, 'To Do list can\'t be empty');
        $this->title = $title;
    }

    public function toString(): string
    {
        return $this->title;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }
}