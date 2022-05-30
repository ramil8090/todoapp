<?php

declare(strict_types=1);


namespace App\TodoList\Domain\ValueObject;


class TaskDescription
{
    private string $description;

    /**
     * @param string $description
     */
    private function __construct(string $description)
    {
        $this->description = $description;
    }

    public static function fromString(string $description): self
    {
        return new self($description);
    }

    public function toString():string
    {
        return $this->description;
    }

    public function __toString(): string
    {
        return $this->description;
    }
}