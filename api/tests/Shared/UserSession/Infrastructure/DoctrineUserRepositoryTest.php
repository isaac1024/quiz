<?php

declare(strict_types=1);

namespace TestsShared\UserSession\Infrastructure;

use Quiz\Tests\UserSession\Domain\UserObjectMother;
use Quiz\UserSession\Infrastructure\DoctrineUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineUserRepositoryTest extends KernelTestCase
{
    private DoctrineUserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        /** @var DoctrineUserRepository repository */
        $this->repository = $kernel->getContainer()->get(DoctrineUserRepository::class);
    }

    public function testCreateUser(): void
    {
        $user = UserObjectMother::make();

        $this->repository->save($user);

        self::addToAssertionCount(1);
    }
}
