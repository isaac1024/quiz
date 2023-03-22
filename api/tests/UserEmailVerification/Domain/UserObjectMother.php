<?php

declare(strict_types=1);

namespace Quiz\Tests\UserEmailVerification\Domain;

use Faker\Factory;
use Quiz\Shared\Domain\Models\UserId;
use Quiz\Tests\Shared\Domain\Models\UserIdObjectMother;
use Quiz\UserEmailVerification\Domain\Email;
use Quiz\UserEmailVerification\Domain\User;
use Quiz\UserEmailVerification\Domain\Verified;

final class UserObjectMother
{
    public static function make(
        ?UserId $userId = null,
        ?Email $email = null,
        ?string $name = null,
        ?Verified $verified = null,
    ): User {
        $faker = Factory::create();
        return new User(
            $userId ?? UserIdObjectMother::make(),
            $email ?? EmailObjectMother::make(),
            $name ?? $faker->name(),
            $verified ?? VerifiedObjectMother::makeNotVerified(),
        );
    }
}
