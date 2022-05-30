<?php

declare(strict_types=1);


namespace App\TodoList\Domain\ValueObject;


use Assert\Assertion;
use Assert\AssertionFailedException;

class TaskState
{
    const IN_PROGRESS = 10;
    const IS_COMPLETED = 11;

    public static array $states = [
        self::IN_PROGRESS,
        self::IS_COMPLETED,
    ];

    private int $state;

    /**
     * @throws AssertionFailedException
     */
    private function __construct(int $value = self::IN_PROGRESS)
    {
        Assertion::inArray($value, self::$states, 'Task state not found');
        $this->state = $value;
    }


    public static function inProgress(): self
    {
        return new self(self::IN_PROGRESS);
    }

    public static function isCompleted(): self
    {
        return new self(self::IS_COMPLETED);
    }

    /**
     * @throws AssertionFailedException
     */
    public static function create(int $state): self
    {
        return new self($state);
    }

    public function equalTo(self $taskState): bool
    {
        return $this->state === $taskState->state;
    }

    public function __toString(): string
    {
        return (string) $this->state;
    }

    public function toString(): string
    {
        return (string) $this->state;
    }
}