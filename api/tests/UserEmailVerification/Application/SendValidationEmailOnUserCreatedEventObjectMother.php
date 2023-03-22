<?php

declare(strict_types=1);

namespace Quiz\Tests\UserEmailVerification\Application;

use DateTimeImmutable;
use Faker\Factory;
use Quiz\Shared\Domain\Models\DateTimeUtils;
use Quiz\Shared\Domain\Models\UuidUtils;
use Quiz\Tests\Shared\Domain\Models\UserIdObjectMother;
use Quiz\Tests\UserEmailVerification\Domain\EmailObjectMother;
use Quiz\UserEmailVerification\Application\SendValidationEmailOnUserCreatedEvent;

final class SendValidationEmailOnUserCreatedEventObjectMother
{
    public static function make(
        ?string $id = null,
        ?string $email = null,
        ?string $name = null,
        ?string $eventId = null,
        ?DateTimeImmutable $occurredOn = null,
    ): SendValidationEmailOnUserCreatedEvent {
        $faker = Factory::create();
        $eventData = [
            'id' => $eventId ?? UuidUtils::random(),
            'data' => [
                'id' => $id ?? UserIdObjectMother::make()->value,
                'attributes' => [
                    'email' => $email ?? EmailObjectMother::make()->value,
                    'name' => $name ?? $faker->name(),
                ],
            ],
            'occurredOn' => $occurredOn ?? DateTimeUtils::format(DateTimeUtils::now()),
        ];

        return SendValidationEmailOnUserCreatedEvent::fromConsumer($eventData);
    }
}
