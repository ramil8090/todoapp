<?php

declare(strict_types=1);


namespace App\Shared\Infrastructure\Event\Consumer;


use App\Shared\Domain\Event\DomainEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class LogEventsConsumer implements MessageSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(DomainEvent $event)
    {
        $serializedEvent = json_encode($event->serialize());
        $this->logger->info($serializedEvent);
    }

    public static function getHandledMessages(): iterable
    {
        yield DomainEvent::class => [
            'from_transport' => 'events',
            'bus' => 'messenger.bus.event.async',
        ];
    }
}