<?php

declare(strict_types=1);

namespace Quiz\Shared\Infrastructure\Bus;

use Quiz\Shared\Domain\Bus\Command;
use Quiz\Shared\Domain\Bus\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class SymfonyCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
