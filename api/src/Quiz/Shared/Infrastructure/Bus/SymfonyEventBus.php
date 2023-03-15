<?php

declare(strict_types=1);

namespace Quiz\Shared\Infrastructure\Bus;

use Quiz\Shared\Domain\Bus\Event;
use Quiz\Shared\Domain\Bus\EventBus;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class SymfonyEventBus implements EventBus
{
    public function __construct(private MessageBusInterface $eventBus)
    {
    }

    public function publish(Event ...$events): void
    {
        foreach ($events as $event) {
            $this->eventBus->dispatch($event, [
                new AmqpStamp($event::type(), AMQP_NOPARAM, []),
            ]);
        }
    }
}
