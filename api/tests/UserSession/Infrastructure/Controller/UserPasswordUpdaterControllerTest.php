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
        /** @var UserRepository $userRepository */
        $userRepository = $this->getRepository(UserRepository::class);

        $oldPassword = PasswordObjectMother::raw();
        $user = UserObjectMother::make(password: PasswordObjectMother::make($oldPassword));
        $userRepository->save($user);

        $request = UserPasswordUpdateRequestObjectMother::makeArray($oldPassword);

        $this->login($user->email()->value, $oldPassword);
        $this->json('PUT', sprintf('/user/%s/password', $user->userId()->value), $request);
        $this->asserStatusCode(200);
    }
}
