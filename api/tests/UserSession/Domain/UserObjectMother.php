<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Domain;

use Quiz\UserSession\Application\UserCreatorCommand;
use Quiz\UserSession\Domain\Email;
use Quiz\UserSession\Domain\Name;
use Quiz\UserSession\Domain\Password;
use Quiz\UserSession\Domain\User;
use Quiz\UserSession\Domain\UserId;

final class UserObjectMother
{
    public static function make(
        ?UserId $userId = null,
        ?Email $email = null,
        ?Name $name = null,
        ?Password $password = null,
    ): User {
        return new User(
            $userId ?? UserIdObjectMother::make(),
            $email ?? EmailObjectMother::make(),
            $name ?? NameObjectMother::make(),
            $password ?? PasswordObjectMother::make(),
        );
    }

    public static function fromUserCreatorCommand(UserCreatorCommand $command): User
    {
        return UserObjectMother::make(
            UserIdObjectMother::make($command->userId),
            EmailObjectMother::make($command->email),
            NameObjectMother::make($command->name),
            PasswordObjectMother::make($command->password),
        );
    }
}
