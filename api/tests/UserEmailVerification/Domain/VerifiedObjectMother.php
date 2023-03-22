<?php

declare(strict_types=1);

namespace Quiz\Tests\UserEmailVerification\Domain;

use DateTimeImmutable;
use Quiz\UserEmailVerification\Domain\Verified;

final class VerifiedObjectMother
{
    public static function make(
        bool $isVerified,
        ?string $token = null,
        ?DateTimeImmutable $expiration = null,
    ): Verified {
        return new Verified($isVerified, $token, $expiration);
    }

    public static function makeNotVerified(?string $token = null, ?DateTimeImmutable $expiration = null): Verified
    {
        return VerifiedObjectMother::make(false, $token, $expiration);
    }

    public static function makeVerified(): Verified
    {
        return VerifiedObjectMother::make(true);
    }
}
