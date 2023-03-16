<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Models;

use Quiz\Shared\Domain\QuizException;

final class DateTimeUtilsException extends QuizException
{
    public static function invalidDateTimeFormat(string $dateTime, string $format): DateTimeUtilsException
    {
        return new DateTimeUtilsException(
            "invalid_datetime_format",
            sprintf("Date time '%s' is not valid. Valid format '%s'", $dateTime, $format)
        );
    }
}
