<?php

namespace Quiz\Tests\UserEmailVerification\Infrastructure\Repository;

use Quiz\Shared\Domain\Models\DateTimeUtils;
use Quiz\Shared\Domain\Models\UserId;
use Quiz\Tests\Shared\Infrastructure\PhpUnit\IntegrationTestCase;
use Quiz\Tests\UserEmailVerification\Domain\VerifiedObjectMother;
use Quiz\Tests\UserSession\Domain\UserObjectMother;
use Quiz\UserEmailVerification\Infrastructure\Repository\DoctrineUserRepository;
use ReflectionClass;

class DoctrineUserRepositoryTest extends IntegrationTestCase
{
    private DoctrineUserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository ??= $this->get(DoctrineUserRepository::class);
    }

    public function testFindUser(): void
    {
        $userId = $this->initUser();
        $user = $this->repository->find($userId);

        self::assertEquals($userId, $user->userId());
    }

    public function testSaveUser(): void
    {
        $userId = $this->initUser();
        $user = $this->repository->find($userId);
        $userReflection = new ReflectionClass($user);
        $userReflection->getProperty('verified')->setValue($user, VerifiedObjectMother::makeVerified());

        $this->repository->save($user);

        self::addToAssertionCount(1);
    }

    public function testFindUserByToken(): void
    {
        $token = bin2hex(random_bytes(64));
        $userId = $this->initUser();
        $user = $this->repository->find($userId);
        $userReflection = new ReflectionClass($user);
        $userReflection->getProperty('verified')->setValue($user, VerifiedObjectMother::makeNotVerified(
            $token,
            DateTimeUtils::fromRelative('+15 min'),
        ));
        $this->repository->save($user);

        self::assertEquals($user, $this->repository->findByToken($token));
    }

    protected function initUser(): UserId
    {
        $user = UserObjectMother::make();
        $this->get(\Quiz\UserSession\Infrastructure\Repository\DoctrineUserRepository::class)->save($user);

        return $user->userId();
    }
}
