<?php

declare(strict_types=1);

namespace Quiz\UserEmailVerification\Domain;

use DateTimeImmutable;
use Quiz\Shared\Domain\Models\DateTimeUtils;

final readonly class Verified
{
    public function __construct(public bool $isVerified, public ?string $token, public ?DateTimeImmutable $expiration)
    {
    }

    public function generateToken(): Verified
    {
        return new Verified(
            false,
            bin2hex(random_bytes(64)),
            DateTimeUtils::fromRelative('+15 minutes')
        );
    }

    public function verify(): Verified
    {
        return new Verified(true, null, null);
    }

    public function isExpired(): bool
    {
        return new DateTimeImmutable() > $this->expiration;
    }
}
