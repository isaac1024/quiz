<?php

declare(strict_types=1);

namespace Quiz\UserEmailVerification\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Quiz\Shared\Domain\Models\UserId;
use Quiz\UserEmailVerification\Domain\User;
use Quiz\UserEmailVerification\Domain\UserRepository;

final class DoctrineUserRepository implements UserRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function find(UserId $userId): ?User
    {
        return $this->entityManager->find(User::class, $userId->value);
    }

    public function findByToken(string $token): ?User
    {
        $user = $this->entityManager->getRepository(User::class)->findBy(['verified.token' => $token], null, 1);
        return $user ? $user[0] : null;
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
