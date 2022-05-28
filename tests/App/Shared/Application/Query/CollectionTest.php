<?php

declare(strict_types=1);


namespace Tests\App\Shared\Application\Query;


use App\Shared\Application\Query\Collection;
use App\Shared\Application\Query\Item;
use App\Shared\Infrastructure\Persistence\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws NotFoundException
     */
    public function given_parameters_should_create_collection_instance()
    {
        $items = [Item::fromPayload('1', 'dummy', ['key' => 'data'])];
        $collection = new Collection(1, 1, 1, $items);
        self::assertSame(1, $collection->page);
        self::assertSame(1, $collection->limit);
        self::assertSame(1,$collection->total);
        self::assertSame($items, $collection->data);
    }

    /**
     * @test
     *
     * @group unit
     */
    public function should_throw_not_found_exception_on_page_not_found()
    {
        self::expectException(NotFoundException::class);

        new Collection(2, 10, 2, []);
    }
}