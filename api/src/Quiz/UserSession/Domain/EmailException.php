<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use Quiz\Shared\Domain\QuizException;

final class EmailException extends QuizException
{
    public static function invalidEmail(string $email): EmailException
    {
        return new EmailException("invalid_email", sprintf("Email %s is not valid.", $email));
    }

    public static function emailAlreadyExist(string $email): EmailException
    {
        return new EmailException("email_already_exist", sprintf("Already exist user with email %s.", $email));
    }
}
