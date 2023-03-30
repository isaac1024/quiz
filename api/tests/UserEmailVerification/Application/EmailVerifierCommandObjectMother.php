<?php

namespace Quiz\Tests\UserEmailVerification\Application;

use Faker\Factory;
use Quiz\UserEmailVerification\Application\EmailVerifierCommand;

class EmailVerifierCommandObjectMother
{
    public static function make(?string $token = null): EmailVerifierCommand
    {
        return new EmailVerifierCommand($token ?? bin2hex(random_bytes(64)));
    }
}
