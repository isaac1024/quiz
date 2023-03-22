<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Application;

use Faker\Factory;
use Quiz\Tests\Shared\Domain\Models\UserIdObjectMother;
use Quiz\Tests\UserSession\Domain\PasswordObjectMother;
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
