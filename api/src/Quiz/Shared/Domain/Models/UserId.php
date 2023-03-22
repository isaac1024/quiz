<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Models;

final readonly class UserId extends Uuid
{
    protected function throwException(): never
    {
        throw UserIdException::invalidUserId($this->value);
    }
}
