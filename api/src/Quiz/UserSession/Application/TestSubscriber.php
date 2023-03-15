<?php

declare(strict_types=1);

namespace Quiz\UserSession\Application;

use Quiz\Shared\Domain\Bus\EventSubscriber;
use Quiz\UserSession\Domain\UserCreated;

final class TestSubscriber implements EventSubscriber
{
    public function dispatch(UserCreated $event): void
    {
        dump($event);
    }
}
