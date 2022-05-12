<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\AsyncEvent;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Infrastructure\Bus\MessageBusExceptionTrait;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class MessengerAsyncEventBus
{
    use MessageBusExceptionTrait;

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @throws Throwable
     */
    public function handle(DomainEvent $event): void
    {
        try {
            $this->messageBus->dispatch($event, [
                new AmqpStamp($event::class),
            ]);
        } catch (HandlerFailedException $error) {
            $this->throwException($error);
        }
    }
}
