<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Models;

use DomainException;

final class DateTimeUtilsException extends DomainException
{
    public static function invalidDateTimeFormat(string $dateTime, string $format): DateTimeUtilsException
    {
        return new DateTimeUtilsException(
            sprintf("Date time '%s' is not valid. Valid format '%s'", $dateTime, $format)
        );
    }
}
