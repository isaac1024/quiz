<?php

declare(strict_types=1);

namespace Quiz\UserSession\Application;

use Quiz\Shared\Domain\Bus\Command;

final readonly class UserCreatorCommand implements Command
{
    public function __construct(
        public string $userId,
        public string $email,
        public string $name,
        public string $password,
    ) {
    }
}
