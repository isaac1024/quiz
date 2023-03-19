<?php

declare(strict_types=1);

namespace App\Service;

use Quiz\Shared\Domain\Bus\EventSubscriber;
use ReflectionClass;

final class EventMapper
{
    private const EVENT_SUBSCRIBER_METHOD = 'dispatch';

    private readonly array $events;

    public function __construct(iterable $eventsSubscribers)
    {
        $events = [];
        foreach ($eventsSubscribers as $eventsSubscriber) {
            $eventClassName = $this->getEventClassNameFromSubscriber($eventsSubscriber);
            if (array_key_exists($eventClassName::type(), $events)) {
                continue;
            }
            $events[$eventClassName::type()] = $eventClassName;
        }

        $this->events = $events;
    }

    private function getEventClassNameFromSubscriber(EventSubscriber $eventsSubscriber): string
    {
        $reflectionClass = new ReflectionClass($eventsSubscriber);
        $eventSubscriberMethod = $reflectionClass->getMethod(self::EVENT_SUBSCRIBER_METHOD);
        $parameters = $eventSubscriberMethod->getParameters();
        return $parameters[0]->getType()->getName();
    }

    public function eventClassName(string $queueName, string $eventType): ?string
    {
        $key = sprintf("%s.%s", $queueName, $eventType);
        return $this->events[$key] ?? null;
    }
}
