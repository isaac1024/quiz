<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use DomainException;

final class UserIdException extends DomainException
{
    public static function invalidUserId(string $userId): UserIdException
    {
        return new UserIdException(sprintf("User id %s is not a valid uuid.", $userId));
    }
}
