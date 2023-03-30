<?php

namespace Quiz\Tests\UserEmailVerification\Infrastructure\Controller;

use Quiz\Shared\Domain\Models\DateTimeUtils;
use Quiz\Tests\Shared\Infrastructure\PhpUnit\AcceptanceTestCase;
use Quiz\Tests\UserEmailVerification\Domain\VerifiedObjectMother;
use Quiz\UserEmailVerification\Domain\UserRepository;
use ReflectionClass;
use Symfony\Component\Messenger\Transport\InMemoryTransport;

class EmailVerificationControllerTest extends AcceptanceTestCase
{
    public function testVerifyUser(): void
    {
        $token = bin2hex(random_bytes(64));
        $this->createUser();
        /** @var UserRepository $userRepository */
        $userRepository = $this->getRepository(UserRepository::class);
        $user = $userRepository->find($this->userId());
        $userReflection = new ReflectionClass($user);
        $userReflection->getProperty('verified')->setValue($user, VerifiedObjectMother::makeNotVerified(
            $token,
            DateTimeUtils::fromRelative('+15 min'),
        ));

        $userRepository->save($user);

        $this->json('GET', sprintf('/user/verify/%s', $token));
        $this->asserStatusCode(302);
    }
}
