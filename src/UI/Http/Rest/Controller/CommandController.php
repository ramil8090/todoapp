<?php

declare(strict_types=1);


namespace UI\Http\Rest\Controller;


use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\CommandInterface;

abstract class CommandController
{
    private CommandBusInterface $commandBus;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    protected function handle(CommandInterface $command): void
    {
        $this->commandBus->handle($command);
    }
}