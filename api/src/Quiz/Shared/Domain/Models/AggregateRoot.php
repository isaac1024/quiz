<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Models;

use Quiz\Shared\Domain\Bus\DomainEvent;
use Quiz\Shared\Domain\Bus\EventBus;

abstract class AggregateRoot
{
    /** @var DomainEvent[] $events */
    private array $events = [];

    final protected function publishEvents(EventBus $eventBus): void
    {
        $eventBus->publish(...$this->events);
        $this->events = [];
    }

    final protected function registerNewEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }
}
