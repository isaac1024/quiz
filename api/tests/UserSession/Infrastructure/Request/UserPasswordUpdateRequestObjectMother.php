<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Infrastructure\Request;

use Quiz\Tests\UserSession\Domain\PasswordObjectMother;

final class UserPasswordUpdateRequestObjectMother
{
    public static function makeArray(?string $oldPassword = null, ?string $newPassword = null): array
    {
        return [
            'oldPassword' => $oldPassword ?? PasswordObjectMother::raw(),
            'newPassword' => $newPassword ?? PasswordObjectMother::raw(),
        ];
    }
}
