<?php

declare(strict_types=1);

namespace TestsShared\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use TestsShared\Request\UserCreatorRequestObjectMother;

class UserCreatorControllerTest extends WebTestCase
{
    public function testRegisterNewUser(): void
    {
        $client = static::createClient();
        $client->jsonRequest(Request::METHOD_POST, '/register', UserCreatorRequestObjectMother::makeArray());

        $this->assertResponseIsSuccessful();
    }
}
