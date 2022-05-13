<?php

declare(strict_types=1);


namespace App\Shared\Infrastructure\Event\Publisher;


use App\Shared\Domain\Event\DomainEvent;
use Assert\Assertion;

class EventDispatcher implements EventDispatcherInterface
{
    private EventPublisherInterface $eventPublisher;

    public function __construct(EventPublisherInterface $eventPublisher)
    {
        $this->eventPublisher = $eventPublisher;
    }

    /**
     * @param DomainEvent[] $events
     */
    public function dispatch(array $events): void
    {
        Assertion::allIsInstanceOf(
            $events,
            DomainEvent::class,
            'Events should implement DomainEvent Interface'
        );
        $this->eventPublisher->recordEvents($events);
    }
}