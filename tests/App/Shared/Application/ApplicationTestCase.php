<?php

declare(strict_types=1);


namespace Tests\App\Shared\Application;


use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Application\Query\QueryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

abstract class ApplicationTestCase extends KernelTestCase implements CommandBusInterface, QueryBusInterface
{
    private ?CommandBusInterface $commandBus;

    private ?QueryBusInterface $queryBus;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->commandBus = $this->service(CommandBusInterface::class);
        $this->queryBus = $this->service(QueryBusInterface::class);
    }

    /**
     * @param QueryInterface $query
     * @return mixed
     *
     */
    public function ask(QueryInterface $query): mixed
    {
        return $this->queryBus->ask($query);
    }

    /**
     * @throws Throwable
     */
    public function handle(CommandInterface $command): void
    {
        $this->commandBus->handle($command);
    }

    /**
     * @param string $serviceId
     * @return object|null
     */
    protected function service(string $serviceId): ?object
    {
        return self::getContainer()->get($serviceId);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->commandBus = null;
        $this->queryBus = null;
    }
}