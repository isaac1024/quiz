<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use Quiz\Shared\Domain\QuizException;

final class NameException extends QuizException
{
    public static function nameWithWhitespaces(string $name): NameException
    {
        return new NameException(
            "name_with_whitespaces",
            sprintf("Name '%s' contain whitespaces at first or end.", $name)
        );
    }

    public static function emptyName(): NameException
    {
        return new NameException("empty_name", "Name is empty.");
    }
}
