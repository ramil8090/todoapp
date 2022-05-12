<?php

declare(strict_types=1);


namespace App\Shared\Infrastructure\Event\Publisher;


use App\Shared\Domain\AggregateRoot;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events as DoctrineEvents;

class EventsCommitter implements EventSubscriber
{
    private array $entities = [];

    private AsyncEventPublisher $asyncEventPublisher;

    public function __construct(AsyncEventPublisher $asyncEventPublisher)
    {
        $this->asyncEventPublisher = $asyncEventPublisher;
    }

    public function onFlush(OnFlushEventArgs $args) {

        $insertEntities = $args->getEntityManager()
            ->getUnitOfWork()
            ->getScheduledEntityInsertions();

        $updateEntities = $args->getEntityManager()
            ->getUnitOfWork()
            ->getScheduledEntityUpdates();

        $this->entities = array_merge($this->entities, $insertEntities);
        $this->entities = array_merge($this->entities, $updateEntities);
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $entities = $this->entities;

        foreach ($entities as $entity) {

            if (!$entity instanceof AggregateRoot) continue;

            $events = $entity->releaseEvents();
            $this->asyncEventPublisher->recordEvents($events);
        }
    }

    public function getSubscribedEvents(): array
    {
        return [
            DoctrineEvents::onFlush,
            DoctrineEvents::postFlush
        ];
    }
}