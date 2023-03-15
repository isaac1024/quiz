<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use Quiz\Shared\Domain\Models\Uuid;

final readonly class UserId extends Uuid
{
    protected function throwException(): never
    {
        throw UserIdException::invalidUserId($this->value);
    }
}
