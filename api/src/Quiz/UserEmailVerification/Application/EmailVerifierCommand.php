<?php

declare(strict_types=1);

namespace Quiz\UserEmailVerification\Application;

use Quiz\Shared\Domain\Bus\Command;

final readonly class EmailVerifierCommand implements Command
{
    public function __construct(public string $token)
    {
    }
}
