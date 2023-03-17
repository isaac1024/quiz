<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Application;

use Faker\Factory;
use Quiz\Tests\UserSession\Domain\PasswordObjectMother;
use Quiz\Tests\UserSession\Domain\UserIdObjectMother;
use Quiz\UserSession\Application\UserPasswordUpdaterCommand;

class UserPasswordUpdaterCommandObjectMother
{
    public static function make(
        ?string $userId = null,
        ?string $oldPassword = null,
        ?string $newPassword = null,
    ): UserPasswordUpdaterCommand {
        $faker = Factory::create();
        return new UserPasswordUpdaterCommand(
            $userId ?? UserIdObjectMother::make()->value,
            $oldPassword ?? PasswordObjectMother::raw(),
            $newPassword ?? PasswordObjectMother::raw(),
        );
    }
}
