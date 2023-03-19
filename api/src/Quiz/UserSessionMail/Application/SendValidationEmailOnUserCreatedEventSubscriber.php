<?php

declare(strict_types=1);

namespace Quiz\UserSessionMail\Application;

use Quiz\Shared\Domain\Bus\EventSubscriber;

final class SendValidationEmailOnUserCreatedEventSubscriber implements EventSubscriber
{
    public function dispatch(SendValidationEmailOnUserCreatedEvent $event): void
    {
        dump($event);
    }
}
