<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpReceivedStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final readonly class AmqpEventMapperMiddleware implements MiddlewareInterface
{
    public function __construct(private EventMapper $eventMapper)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        /** @var AmqpReceivedStamp $amqpStamp */
        $amqpStamp = $envelope->last(AmqpReceivedStamp::class);

        if (!$amqpStamp) {
            return $stack->next()->handle($envelope, $stack);
        }

        $eventData = json_decode((string)$envelope->getMessage(), true);
        $eventClassName = $this->eventMapper->eventClassName(
            $amqpStamp->getQueueName(),
            $amqpStamp->getAmqpEnvelope()->getType(),
        );

        if (!$eventClassName) {
            return $stack->next()->handle($envelope, $stack);
        }

        $stamps = array_merge(...array_values($envelope->all()));
        return $stack->next()->handle(
            new Envelope($eventClassName::fromConsumer($eventData), $stamps),
            $stack,
        );
    }
}
