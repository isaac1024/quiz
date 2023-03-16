<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Infrastructure\Request;

use Quiz\Tests\UserSession\Domain\EmailObjectMother;
use Quiz\Tests\UserSession\Domain\NameObjectMother;
use Quiz\Tests\UserSession\Domain\PasswordObjectMother;

class UserCreatorRequestObjectMother
{
    public static function makeArray(
        ?string $email = null,
        ?string $name = null,
        ?string $password = null
    ): array {
        return [
            'email' => $email ?? EmailObjectMother::make()->value,
            'name' => $name ?? NameObjectMother::make()->value,
            'password' => $password ?? PasswordObjectMother::make()->value,
        ];
    }
}
