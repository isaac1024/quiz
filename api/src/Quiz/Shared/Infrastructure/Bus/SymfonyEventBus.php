<?php

declare(strict_types=1);

namespace Quiz\Shared\Infrastructure\Bus;

use Quiz\Shared\Domain\Bus\DomainEvent;
use Quiz\Shared\Domain\Bus\EventBus;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class SymfonyEventBus implements EventBus
{
    public function __construct(private MessageBusInterface $eventBus)
    {
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->eventBus->dispatch($event, [
                new AmqpStamp($event->type(), AMQP_NOPARAM, [
                    'type' => $event->type(),
                    'content_type' => 'application/json',
                    'content_encoding' => 'utf-8',
                    'message_id' => $event->eventId,
                    'timestamp' => $event->occurredOn->getTimestamp(),
                ]),
            ]);
        }
    }
}
