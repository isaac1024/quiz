<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Domain;

use Quiz\UserSession\Domain\User;
use Quiz\UserSession\Domain\UserCreated;

final class UserCreatedObjectMother
{
    public static function make(?string $id = null, ?string $email = null, ?string $name = null): UserCreated
    {
        return new UserCreated(
            $id ?? UserIdObjectMother::make()->value,
            $email ?? EmailObjectMother::make()->value,
            $name ?? NameObjectMother::make()->value,
        );
    }

    public static function fromUser(User $user): UserCreated
    {
        return UserCreatedObjectMother::make(
            $user->userId()->value,
            $user->email()->value,
            $user->name()->value,
        );
    }
}
