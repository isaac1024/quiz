<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Models;

use DateTimeImmutable;

final class DateTimeUtils
{
    public static function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public static function fromString(string $dateTime): DateTimeImmutable
    {
        $datetime = DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $dateTime);
        if ($datetime === false) {
            throw DateTimeUtilsException::invalidDateTimeFormat($dateTime, DateTimeImmutable::ATOM);
        }

        return $datetime;
    }

    public static function fromRelative(string $relative): DateTimeImmutable
    {
        return new DateTimeImmutable($relative);
    }

    public static function format(DateTimeImmutable $dateTime): string
    {
        return $dateTime->format(DateTimeImmutable::ATOM);
    }
}
