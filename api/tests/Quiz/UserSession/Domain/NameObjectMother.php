<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Domain;

use Faker\Factory;
use Quiz\UserSession\Domain\Name;

final class NameObjectMother
{
    public static function make(?string $name = null): Name
    {
        $faker = Factory::create();
        return new Name($name ?? $faker->name());
    }
}
