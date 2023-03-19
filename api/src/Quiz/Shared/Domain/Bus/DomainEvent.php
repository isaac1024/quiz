<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Bus;

use DateTimeImmutable;
use Quiz\Shared\Domain\Models\DateTimeUtils;
use Quiz\Shared\Domain\Models\UuidUtils;

abstract class DomainEvent
{
    public readonly string $eventId;
    public readonly DateTimeImmutable $occurredOn;

    public function __construct(public readonly string $aggregateId)
    {
        $this->eventId = UuidUtils::random();
        $this->occurredOn = DateTimeUtils::now();
    }

    abstract public function type(): string;

    abstract public function attributes(): array;
}
