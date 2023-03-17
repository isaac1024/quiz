<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use Quiz\Shared\Domain\Criteria\Criteria;

interface UserRepository
{
    public function find(UserId $userId): ?User;
    public function byCriteria(Criteria $criteria): UserCollection;
    public function save(User $user): void;
}
