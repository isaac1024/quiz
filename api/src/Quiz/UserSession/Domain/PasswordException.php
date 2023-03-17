<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use Quiz\Shared\Domain\QuizException;

final class PasswordException extends QuizException
{
    public static function tooShort(int $min): PasswordException
    {
        return new PasswordException(
            "short_password",
            sprintf("Password is too short. Min length %d.", $min)
        );
    }

    public static function notMatch(): PasswordException
    {
        return new PasswordException("not_match_password", "Current password is not correct.");
    }

    public static function notChanged(): PasswordException
    {
        return new PasswordException("not_changed_password", "New password is same.");
    }
}
