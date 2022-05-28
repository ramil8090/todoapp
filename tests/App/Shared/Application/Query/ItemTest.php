<?php

declare(strict_types=1);


namespace Tests\App\Shared\Application\Query;


use App\Shared\Application\Query\Item;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ItemTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     */
    public function given_payload_should_create_an_item_successfully()
    {
        $item = Item::fromPayload(
            $id = Uuid::uuid4()->toString(),
            $type= 'EntityClassName',
            $payload = [
                'id' => 1,
                'key' => 'value',
            ]
        );

        self::assertSame($id, $item->id);
        self::assertSame($type, $item->type);
        self::assertSame($payload, $item->resource);
        self::assertSame([], $item->relationships);
    }
}