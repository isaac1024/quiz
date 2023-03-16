<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use Quiz\Shared\Domain\QuizException;

final class PasswordException extends QuizException
{
    public static function tooShort(string $password, int $min): PasswordException
    {
        return new PasswordException(
            "short_password",
            sprintf("Password %s is too short. Min length %d.", $password, $min)
        );
    }
}
