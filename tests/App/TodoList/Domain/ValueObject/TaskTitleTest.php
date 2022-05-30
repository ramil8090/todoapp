<?php

declare(strict_types=1);


namespace Tests\App\TodoList\Domain\ValueObject;


use App\TodoList\Domain\ValueObject\TaskTitle;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class TaskTitleTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws AssertionFailedException
     */
    public function given_string_should_create_a_task_title()
    {
        $title = TaskTitle::fromString('title');
        self::assertSame('title', $title->toString());
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws AssertionFailedException
     */
    public function given_empty_string_should_throw_an_exception()
    {
        self::expectException(AssertionFailedException::class);

        TaskTitle::fromString('');
    }
}