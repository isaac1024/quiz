<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Domain;

use Quiz\UserSession\Domain\UserPasswordUpdated;

final class UserPasswordUpdatedObjectMother
{
    public static function make(?string $id = null): UserPasswordUpdated
    {
        return new UserPasswordUpdated($id ?? UserIdObjectMother::make()->value);
    }
}
