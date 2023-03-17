<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use DateTimeImmutable;
use Quiz\Shared\Domain\Bus\Event;
use Quiz\Shared\Domain\Models\DateTimeUtils;

final class UserPasswordUpdated extends Event
{
    public static function fromConsumer(array $eventData): static
    {
        return new UserPasswordUpdated(
            $eventData['data']['id'],
            $eventData['id'],
            DateTimeUtils::fromString($eventData['occurredOn']),
        );
    }

    public static function type(): string
    {
        return 'quiz.user.password_updated';
    }

    public function attributes(): array
    {
        return [];
    }
}
