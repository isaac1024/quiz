<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use Quiz\Shared\Domain\Bus\DomainEvent;
use Quiz\Shared\Domain\Models\DateTimeUtils;

final class UserPasswordUpdated extends DomainEvent
{
//    public static function fromConsumer(array $eventData): static
//    {
//        return new UserPasswordUpdated(
//            $eventData['data']['id'],
//            $eventData['id'],
//            DateTimeUtils::fromString($eventData['occurredOn']),
//        );
//    }

    public function type(): string
    {
        return 'quiz.user.password_updated';
    }

    public function attributes(): array
    {
        return [];
    }
}
