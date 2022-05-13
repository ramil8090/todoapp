<?php

declare(strict_types=1);


namespace App\Shared\Infrastructure\Event\Publisher;


use App\Shared\Domain\Event\DomainEvent;

interface EventDispatcherInterface
{
    /**
     * @param DomainEvent[] $events
     * @return mixed
     */
    public function dispatch(array $events): void;
}