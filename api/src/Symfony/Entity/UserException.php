<?php

declare(strict_types=1);

namespace App\Entity;

use Quiz\Shared\Domain\QuizException;

final class UserException extends QuizException
{
    public static function unverifiedEmail(): UserException
    {
        return new UserException('unverified_email', 'Unverified user email');
    }
}
