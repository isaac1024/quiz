<?php

declare(strict_types=1);

namespace Quiz\UserSession\Infrastructure\Request;

final readonly class UserPasswordUpdateRequest
{
    public function __construct(
        public string $oldPassword,
        public string $newPassword,
    ) {
    }
}
