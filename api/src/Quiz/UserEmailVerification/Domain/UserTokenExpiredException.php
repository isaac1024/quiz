<?php

declare(strict_types=1);

namespace Quiz\UserEmailVerification\Domain;

use Quiz\Shared\Domain\QuizException;

final class UserTokenExpiredException extends QuizException
{
    public static function expired(): UserTokenExpiredException
    {
        return new UserTokenExpiredException('user_email_verification_expired', 'Email verification token expired.');
    }
}
