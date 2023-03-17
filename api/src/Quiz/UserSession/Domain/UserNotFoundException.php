<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use Quiz\Shared\Domain\QuizException;

final class UserNotFoundException extends QuizException
{
    public static function notFound(string $id): UserNotFoundException
    {
        return new UserNotFoundException('user_id_not_found', sprintf("User %s not found.", $id));
    }
}
