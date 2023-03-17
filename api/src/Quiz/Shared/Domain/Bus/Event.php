<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Bus;

use DateTimeImmutable;
use Quiz\Shared\Domain\Models\DateTimeUtils;
use Quiz\Shared\Domain\Models\UuidUtils;

abstract class Event
{
    public readonly string $eventId;
    public readonly DateTimeImmutable $occurredOn;

    public function __construct(
        public readonly string $aggregateId,
        ?string $eventId = null,
        ?DateTimeImmutable $occurredOn = null
    ) {
        $this->eventId = $eventId ?: UuidUtils::random();
        $this->occurredOn = $occurredOn ?: DateTimeUtils::now();
    }

    abstract public static function fromConsumer(array $eventData): static;

    abstract public static function type(): string;

    abstract public function attributes(): array;
}
