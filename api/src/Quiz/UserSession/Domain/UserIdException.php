<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use Quiz\Shared\Domain\QuizException;

final class UserIdException extends QuizException
{
    public static function invalidUserId(string $userId): UserIdException
    {
        return new UserIdException("invalid_user_id", sprintf("User id %s is not a valid uuid.", $userId));
    }
}
