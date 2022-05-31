<?php

declare(strict_types=1);


namespace Tests\UI\Http\Rest\Response;


use App\Shared\Application\Query\Collection;
use App\Shared\Application\Query\Item;
use App\Shared\Domain\Exception\DateTimeException;
use App\Shared\Infrastructure\Persistence\Exception\NotFoundException;
use App\User\Domain\User;
use App\User\Domain\ValueObject\Auth\Credentials;
use App\User\Domain\ValueObject\Auth\HashedPassword;
use App\User\Domain\ValueObject\Email;
use Assert\AssertionFailedException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tests\App\User\Domain\Specification\DummyUniqueEmailSpecification;
use UI\Http\Rest\Response\OpenApi;

class OpenApiResponseTest extends TestCase
{
    /**
     * @test
     *
     * @group unit
     *
     * @throws AssertionFailedException
     * @throws DateTimeException
     * @throws NotFoundException
     */
    public function format_collection()
    {
        $users = [
            Item::fromPayload('1', 'User', self::createUserView(Uuid::uuid4(), Email::fromString('user1@email.com'))),
            Item::fromPayload('2', 'User', self::createUserView(Uuid::uuid4(), Email::fromString('user2@email.com'))),
        ];

        $collection = OpenApi::collection(new Collection(1, 10, \count($users), $users))->getContent();
        $response = \json_decode($collection, true);

        self::assertArrayHasKey('data', $response);
        self::assertArrayHasKey('meta', $response);
        self::assertArrayHasKey('total', $response['meta']);
        self::assertArrayHasKey('page', $response['meta']);
        self::assertArrayHasKey('size', $response['meta']);
        self::assertCount(2, $response['data']);
    }

    /**
     * @test
     *
     * @group
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    public function one_output()
    {
        $user = self::createUserView(Uuid::uuid4(), Email::fromString('user@email.com'));

        $item = Item::fromPayload('1', 'User', $user);

        $response = \json_decode(OpenApi::one($item)->getContent(), true);

        self::assertArrayHasKey('data', $response);
        self::assertSame('User', $response['data']['type']);
        self::assertCount(4, $response['data']['attributes']);
    }

    /**
     * @param UuidInterface $uuid
     * @param Email $email
     * @return array
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    private static function createUserView(UuidInterface $uuid, Email $email): array
    {
        $user = User::create(
            $uuid,
            new Credentials(
                $email,
                HashedPassword::encode('ljalsjdlajsdljlajsd'),
            ),
            new DummyUniqueEmailSpecification()
        );

        return $user->serialize();
    }
}