<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use DateTimeImmutable;
use Quiz\Shared\Domain\Bus\Event;
use Quiz\Shared\Domain\Models\DateTimeUtils;

final class UserCreated extends Event
{
    public function __construct(
        string $id,
        private readonly string $email,
        private readonly string $name,
        ?string $eventId = null,
        ?DateTimeImmutable $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
    }

    public static function fromConsumer(array $eventData): static
    {
        return new UserCreated(
            $eventData['data']['id'],
            $eventData['data']['attributes']['email'],
            $eventData['data']['attributes']['name'],
            $eventData['id'],
            DateTimeUtils::fromString($eventData['occurredOn']),
        );
    }

    public static function type(): string
    {
        return 'quiz.user.created';
    }

    public function attributes(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
        ];
    }
}
