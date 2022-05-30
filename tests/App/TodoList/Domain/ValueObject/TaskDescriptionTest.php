<?php

declare(strict_types=1);


namespace Tests\App\TodoList\Domain\ValueObject;


use App\TodoList\Domain\ValueObject\TaskDescription;
use PHPUnit\Framework\TestCase;


class TaskDescriptionTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     */
    public function given_a_string_should_create_task_description()
    {
        $description = TaskDescription::fromString('description');
        self::assertSame('description', $description->toString());

        $description = TaskDescription::fromString('');
        self::assertEmpty($description->toString());
    }
}