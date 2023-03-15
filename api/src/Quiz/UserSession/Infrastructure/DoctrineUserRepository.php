<?php

declare(strict_types=1);

namespace Quiz\UserSession\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use Quiz\UserSession\Domain\User;
use Quiz\UserSession\Domain\UserRepository;

final readonly class DoctrineUserRepository implements UserRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
