<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Application;

use Quiz\Tests\UserSession\Domain\EmailObjectMother;
use Quiz\Tests\UserSession\Domain\NameObjectMother;
use Quiz\Tests\UserSession\Domain\PasswordObjectMother;
use Quiz\Tests\UserSession\Domain\UserIdObjectMother;
use Quiz\UserSession\Application\UserCreatorCommand;

final class UserCreatorCommandObjectMother
{
    public static function make(
        ?string $userId = null,
        ?string $email = null,
        ?string $name = null,
        ?string $password = null,
    ): UserCreatorCommand {
        return new UserCreatorCommand(
            $userId ?? UserIdObjectMother::make()->value,
            $email ?? EmailObjectMother::make()->value,
            $name ?? NameObjectMother::make()->value,
            $password ?? PasswordObjectMother::raw(),
        );
    }
}
