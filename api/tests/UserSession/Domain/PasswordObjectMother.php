<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Domain;

use Faker\Factory;
use Quiz\UserSession\Domain\Password;

final class PasswordObjectMother
{
    public const MINLENGTH = 12;
    public const MAXLENGTH = 36;

    public static function make(?string $password = null): Password
    {
        return new Password($password ?? PasswordObjectMother::raw());
    }

    public static function raw(): string
    {
        return Factory::create()->password(self::MINLENGTH, self::MAXLENGTH);
    }
}
