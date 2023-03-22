<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\UserException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isVerified) {
            throw UserException::unverifiedEmail();
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
