<?php

declare(strict_types=1);

namespace Quiz\Tests\Shared\Domain\Models;

use Quiz\Shared\Domain\Models\UserId;
use Quiz\Shared\Domain\Models\UuidUtils;

final class UserIdObjectMother
{
    public static function make(?string $uuid = null): UserId
    {
        return new UserId($uuid ?? UuidUtils::random());
    }
}
