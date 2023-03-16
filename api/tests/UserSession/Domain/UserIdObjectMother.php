<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Domain;

use Quiz\Shared\Domain\Models\UuidUtils;
use Quiz\UserSession\Domain\UserId;

final class UserIdObjectMother
{
    public static function make(?string $uuid = null): UserId
    {
        return new UserId($uuid ?? UuidUtils::random());
    }
}
