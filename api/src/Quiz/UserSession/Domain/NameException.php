<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use DomainException;

final class NameException extends DomainException
{
    public static function nameWithWhitespaces(string $name): NameException
    {
        return new NameException(sprintf("Name '%s' contain whitespaces at first or end.", $name));
    }

    public static function emptyName(): NameException
    {
        return new NameException("Name is empty.");
    }
}
