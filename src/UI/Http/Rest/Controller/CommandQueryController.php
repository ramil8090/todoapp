<?php

declare(strict_types=1);


namespace UI\Http\Rest\Controller;


use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Shared\Application\Query\QueryBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class CommandQueryController extends QueryController
{
    private CommandBusInterface $commandBus;

    /**
     * @param CommandBusInterface $commandBus
     * @param QueryBusInterface $queryBus
     * @param UrlGeneratorInterface $router
     */
    public function __construct(CommandBusInterface $commandBus, QueryBusInterface $queryBus, UrlGeneratorInterface $router)
    {
        parent::__construct($queryBus, $router);
        $this->commandBus = $commandBus;
    }


    protected function handle(CommandInterface $command): void
    {
        $this->commandBus->handle($command);
    }
}