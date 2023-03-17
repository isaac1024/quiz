<?php

declare(strict_types=1);

namespace Quiz\UserSession\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Quiz\Shared\Domain\Criteria\Criteria;
use Quiz\Shared\Infrastructure\Doctrine\DomainCriteriaToDoctrineCriteria;
use Quiz\UserSession\Domain\Email;
use Quiz\UserSession\Domain\Name;
use Quiz\UserSession\Domain\Password;
use Quiz\UserSession\Domain\User;
use Quiz\UserSession\Domain\UserCollection;
use Quiz\UserSession\Domain\UserId;
use Quiz\UserSession\Domain\UserRepository;

final readonly class DoctrineUserRepository implements UserRepository
{
    private const FIELD_MAPPER = [
        UserId::class => 'userId',
        Email::class => 'email.value',
        Name::class => 'name.value',
        Password::class => 'password.value',
    ];

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function find(UserId $userId): ?User
    {
        return $this->entityManager->find(User::class, $userId->value);
    }

    public function byCriteria(Criteria $criteria): UserCollection
    {
        $doctrineCriteria = DomainCriteriaToDoctrineCriteria::convert($criteria, self::FIELD_MAPPER);
        $users = $this->entityManager->getRepository(User::class)->matching($doctrineCriteria);

        return new UserCollection(...$users);
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
