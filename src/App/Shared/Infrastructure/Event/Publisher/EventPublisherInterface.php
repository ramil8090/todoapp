<?php

declare(strict_types=1);


namespace App\Shared\Infrastructure\Event\Publisher;


interface EventPublisherInterface
{
    public function publish(): void;

    public function events(): array;
}