<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Domain;

use Faker\Factory;
use Quiz\UserSession\Domain\Email;

final class EmailObjectMother
{
    public static function make(?string $value = null): Email
    {
        $faker = Factory::create();
        return new Email($value ?? $faker->email());
    }
}
