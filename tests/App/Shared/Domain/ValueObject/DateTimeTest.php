<?php

declare(strict_types=1);


namespace App\Shared\Domain\ValueObject;


use App\Shared\Domain\Exception\DateTimeException;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    const BAD_DATETIME = 'bad';

    /**
     * @test
     *
     * @group unit
     *
     * @throws DateTimeException
     */
    public function given_a_bad_formatted_datetime_should_throw_an_excetion_when_we_try_to_create_datetime(): void
    {
        $this->expectException(DateTimeException::class);

        DateTime::fromString(self::BAD_DATETIME);
    }
}