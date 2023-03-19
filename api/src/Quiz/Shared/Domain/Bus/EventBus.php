<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Bus;

interface EventBus
{
    public function publish(DomainEvent ...$events): void;
}
