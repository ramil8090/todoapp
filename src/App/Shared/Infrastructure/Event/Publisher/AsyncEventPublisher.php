<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event\Publisher;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Infrastructure\Bus\AsyncEvent\MessengerAsyncEventBus;
use Assert\Assertion;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

use Throwable;

final class AsyncEventPublisher implements EventSubscriberInterface, EventPublisherInterface
{
    /** @var DomainEvent[] */
    private array $events = [];

    private MessengerAsyncEventBus $bus;

    public function __construct(MessengerAsyncEventBus $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param DomainEvent[] $events
     */
    public function recordEvents(array $events): void
    {
        Assertion::allIsInstanceOf(
            $events,
            DomainEvent::class,
            "Events should implement DomainEvent interface"
        );

        foreach ($events as $event) {
            $this->events[] = $event;
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['clearEvents', 100],
            KernelEvents::TERMINATE => 'publish',
            ConsoleEvents::TERMINATE => 'publish',
        ];
    }

    /**
     * @throws Throwable
     */
    public function publish(): void
    {
        if (empty($this->events)) {
            return;
        }

        foreach ($this->events as $event) {
            $this->bus->handle($event);
        }
    }

    public function clearEvents(): void
    {
        $this->events = [];
    }
}
