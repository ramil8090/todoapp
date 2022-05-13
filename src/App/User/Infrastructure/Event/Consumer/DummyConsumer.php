<?php

declare(strict_types=1);


namespace App\User\Infrastructure\Event\Consumer;


use App\Shared\Infrastructure\Bus\AsyncEvent\AsyncEventHandlerInterface;
use App\User\Domain\Event\UserWasCreated;

class DummyConsumer implements AsyncEventHandlerInterface
{
    public function __invoke(UserWasCreated $event)
    {
        echo json_encode($event->serialize());
    }
}