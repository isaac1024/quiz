<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Models;

use Symfony\Component\Uid\Uuid;

final class UuidUtils
{
    public static function random(): string
    {
        return (string) Uuid::v4();
    }

    public static function isValid(string $uuid): bool
    {
        return Uuid::isValid($uuid);
    }
}
