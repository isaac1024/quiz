<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Bus;

interface EventBus
{
    public function publish(Event ...$events): void;
}
