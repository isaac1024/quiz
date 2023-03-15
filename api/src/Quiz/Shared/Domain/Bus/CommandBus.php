<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Bus;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
