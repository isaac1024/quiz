<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Infrastructure\Controller;

use Quiz\Tests\Shared\Infrastructure\PhpUnit\AcceptanceTestCase;
use Quiz\Tests\UserSession\Domain\PasswordObjectMother;
use Quiz\Tests\UserSession\Domain\UserObjectMother;
use Quiz\Tests\UserSession\Infrastructure\Request\UserPasswordUpdateRequestObjectMother;
use Quiz\UserSession\Domain\UserRepository;

class UserPasswordUpdaterControllerTest extends AcceptanceTestCase
{
    public function testUpdateUserPassword(): void
    {
        $this->createUser();
        $this->verifyUser();
        $request = UserPasswordUpdateRequestObjectMother::makeArray($this->password());

        $this->login();
        $this->json('PUT', sprintf('/user/%s/password', $this->userId()->value), $request);
        $this->asserStatusCode(200);
    }
}
