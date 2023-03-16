<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Infrastructure\Repository;

use Quiz\Shared\Domain\Criteria\Criteria;
use Quiz\Shared\Domain\Criteria\Filter;
use Quiz\Shared\Domain\Criteria\Filters;
use Quiz\Shared\Domain\Criteria\Order;
use Quiz\Tests\Shared\Infrastructure\PhpUnit\IntegrationTestCase;
use Quiz\Tests\UserSession\Domain\UserObjectMother;
use Quiz\UserSession\Domain\Email;
use Quiz\UserSession\Domain\Name;
use Quiz\UserSession\Infrastructure\Repository\DoctrineUserRepository;

class DoctrineUserRepositoryTest extends IntegrationTestCase
{
    private DoctrineUserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository ??= $this->get(DoctrineUserRepository::class);
    }

    public function testCreateUser(): void
    {
        $user = UserObjectMother::make();

        $this->repository->save($user);

        self::addToAssertionCount(1);
    }

    public function testSearchUsersByEmail(): void
    {
        $expectedUser = UserObjectMother::make();
        $this->repository->save($expectedUser);

        $criteria = new Criteria(
            new Filters(new Filter(Email::class, $expectedUser->email())),
            new Order(Name::class),
            10,
            0
        );
        $users = $this->repository->byCriteria($criteria);

        self::assertCount(1, $users);
        self::assertEquals($expectedUser, $users->users[0]);
    }

    public function testSearchTwoUsers(): void
    {
        $this->repository->save(UserObjectMother::make());
        $this->repository->save(UserObjectMother::make());
        $this->repository->save(UserObjectMother::make());

        $criteria = new Criteria(new Filters(), null, 2);
        $users = $this->repository->byCriteria($criteria);

        self::assertCount(2, $users);
    }
}
