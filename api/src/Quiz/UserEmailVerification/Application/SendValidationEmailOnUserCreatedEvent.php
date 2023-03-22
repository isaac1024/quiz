<?php

declare(strict_types=1);

namespace Quiz\UserEmailVerification\Application;

use DateTimeImmutable;
use Quiz\Shared\Domain\Bus\Event;
use Quiz\Shared\Domain\Models\DateTimeUtils;

final readonly class SendValidationEmailOnUserCreatedEvent extends Event
{
    private function __construct(
        string $id,
        public string $email,
        public string $name,
        string $eventId,
        DateTimeImmutable $occurredOn,
    ) {
        parent::__construct($id, $eventId, $occurredOn);
    }

    public static function fromConsumer(array $eventData): static
    {
        return new SendValidationEmailOnUserCreatedEvent(
            $eventData['data']['id'],
            $eventData['data']['attributes']['email'],
            $eventData['data']['attributes']['name'],
            $eventData['id'],
            DateTimeUtils::fromString($eventData['occurredOn']),
        );
    }

    public static function type(): string
    {
        return 'quiz.user.send_validation.quiz.user.created';
    }

    public function attributes(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
        ];
    }
}
