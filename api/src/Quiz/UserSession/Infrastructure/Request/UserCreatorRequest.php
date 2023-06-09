<?php

declare(strict_types=1);

namespace Quiz\UserSession\Infrastructure\Request;

final readonly class UserCreatorRequest
{
    public function __construct(
        public string $email,
        public string $name,
        public string $password,
    ) {
    }
}
