<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use DomainException;

final class PasswordException extends DomainException
{
    public static function tooShort(string $password, int $min): PasswordException
    {
        return new PasswordException(sprintf("Password %s is too short. Min length %d.", $password, $min));
    }
}
