<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

interface UserRepository
{
    public function save(User $user): void;
}
