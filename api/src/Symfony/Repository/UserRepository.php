<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;

final readonly class UserRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }
}
