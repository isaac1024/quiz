<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use DomainException;

final class EmailException extends DomainException
{
    public static function invalidEmail(string $email): EmailException
    {
        return new EmailException(sprintf("Email %s is not valid.", $email));
    }
}
