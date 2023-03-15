<?php

declare(strict_types=1);

namespace App\Service;

use Quiz\Shared\Domain\Bus\Event;
use Quiz\Shared\Domain\Models\DateTimeUtils;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class JsonAmqpSerializer implements SerializerInterface
{
    public function __construct(private readonly EventMapper $eventMapper)
    {
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        $eventData = json_decode($encodedEnvelope['body'], true);
        $eventClassName = $this->eventMapper->eventClassName($eventData['data']['type']);

        return new Envelope($eventClassName::fromConsumer($eventData));
    }

    public function encode(Envelope $envelope): array
    {
        /** @var Event $event */
        $event = $envelope->getMessage();

        return [
            'headers' => $this->getHeaders($event),
            'body' => json_encode($this->getBody($event)),
        ];
    }

    private function getBody(Event $event): array
    {
        return [
            'id' => $event->eventId,
            'data' => [
                'id' => $event->aggregateId,
                'type' => $event::type(),
                'attributes' => $event->attributes(),
            ],
            'occurredOn' => DateTimeUtils::format($event->occurredOn),
        ];
    }

    public function getHeaders(Event $event): array
    {
        return [
            'content-type' => 'application/json',
            'content-encoding' => 'utf-8',
            'message-id' => $event->eventId
        ];
    }
}
