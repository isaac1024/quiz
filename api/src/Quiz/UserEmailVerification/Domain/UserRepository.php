<?php

declare(strict_types=1);

namespace Quiz\UserEmailVerification\Domain;

use Quiz\Shared\Domain\Models\UserId;

interface UserRepository
{
    public function find(UserId $userId): ?User;

    public function findByToken(string $token): ?User;
    public function save(User $user): void;
}
