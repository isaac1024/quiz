<?php

declare(strict_types=1);

namespace Quiz\UserEmailVerification\Domain;

final readonly class Email
{
    public function __construct(public string $value)
    {
    }

    public function isSame(string $email)
    {
        return $this->value === $email;
    }
}
