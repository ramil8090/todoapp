<?php

declare(strict_types=1);


namespace Tests\App\Shared\Infrastructure\Event\Publisher;


use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Infrastructure\Event\Publisher\AsyncEventPublisher;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Transport\TransportInterface;

class EventPublisherTest extends KernelTestCase
{
    private ?AsyncEventPublisher $publisher;

    private ?TransportInterface $transport;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->publisher = $this->service(AsyncEventPublisher::class);
        $this->transport = $this->service('messenger.transport.events');
    }

    /**
     * @test
     *
     * @group integration
     */
    public function events_are_consumed(): void
    {
        $this->publisher->recordEvents([
            new DummyDomainEvent('some_value')
        ]);

        $this->publisher->publish();

        $publishedEvents = $this->transport->get();
        self::assertCount(1, $publishedEvents);

        $event = $publishedEvents[0]->getMessage();
        self::assertInstanceOf(DummyDomainEvent::class, $event);
        self::assertSame('some_value', $event->data);
    }

    protected function tearDown(): void
    {
        $this->publisher = null;
        $this->transport = null;
    }

    /**
     * @return object|null
     */
    protected function service(string $serviceId)
    {
        return self::getContainer()->get($serviceId);
    }
}

class DummyDomainEvent implements DomainEvent
{
    public string $data;

    /**
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function serialize(): array
    {
        return ['data' => $this->data];
    }

    public static function deserialize(array $data): DomainEvent
    {
        return new self('value');
    }
}