<?php

declare(strict_types=1);

namespace App\Service;

use Quiz\Shared\Domain\Bus\DomainEvent;
use Quiz\Shared\Domain\Models\DateTimeUtils;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class JsonAmqpSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        return new Envelope(new AmqpMessage($encodedEnvelope['body']));
    }

    public function encode(Envelope $envelope): array
    {
        $event = $envelope->getMessage();
        $body = $event instanceof DomainEvent ? json_encode($this->getBody($event)) : (string)$event;

        return ['headers' => [], 'body' => $body];
    }

    private function getBody(DomainEvent $event): array
    {
        return [
            'id' => $event->eventId,
            'data' => [
                'id' => $event->aggregateId,
                'type' => $event->type(),
                'attributes' => $event->attributes(),
            ],
            'occurredOn' => DateTimeUtils::format($event->occurredOn),
        ];
    }
}
