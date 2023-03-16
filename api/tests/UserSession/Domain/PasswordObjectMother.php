<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Domain;

use Faker\Factory;
use Quiz\UserSession\Domain\Password;

final class PasswordObjectMother
{
    public static function make(?string $password = null): Password
    {
        $faker = Factory::create();
        return new Password($password ?? $faker->password(12, 36));
    }
}
