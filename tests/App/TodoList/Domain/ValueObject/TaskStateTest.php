<?php

declare(strict_types=1);


namespace Tests\App\TodoList\Domain\ValueObject;


use App\TodoList\Domain\ValueObject\TaskState;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;

class TaskStateTest extends TestCase
{

    /**
     * @test
     *
     * @group unit
     */
    public function should_create_in_progress_task_state()
    {
        $state = TaskState::inProgress();
        self::assertTrue($state->equalTo(TaskState::inProgress()));
    }

    /**
     * @test
     *
     * @group unit
     */
    public function should_create_is_completed_task_state()
    {
        $state = TaskState::isCompleted();
        self::assertTrue($state->equalTo(TaskState::isCompleted()));
    }

    /**
     * @test
     *
     * @group unit
     *
     * @throws AssertionFailedException
     */
    public function given_a_state_value_should_create_a_task_state()
    {
        $state = TaskState::create(TaskState::IN_PROGRESS);
        self::assertTrue($state->equalTo(TaskState::inProgress()));
    }

    /**
     * @test
     *
     * @group unit
     */
    public function given_a_non_exist_state_should_throw_an_exception()
    {
        self::expectException(AssertionFailedException::class);

        TaskState::create(-1_000_000);
    }
}