<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Infrastructure\Controller;

use Quiz\Tests\Shared\Infrastructure\PhpUnit\AcceptanceTestCase;
use Quiz\Tests\UserSession\Infrastructure\Request\UserCreatorRequestObjectMother;

class UserCreatorControllerTest extends AcceptanceTestCase
{
    public function testRegisterNewUser(): void
    {
        $this->json('POST', '/register', UserCreatorRequestObjectMother::makeArray());
        $this->asserStatusCode(201);
    }
}
