<?php

declare(strict_types=1);

namespace Quiz\UserEmailVerification\Application;

use Quiz\Shared\Domain\Bus\CommandHandler;
use Quiz\Shared\Domain\Models\UserId;
use Quiz\UserEmailVerification\Domain\UserNotFoundException;
use Quiz\UserEmailVerification\Domain\UserRepository;

final class EmailVerifierCommandHandler implements CommandHandler
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function dispatch(EmailVerifierCommand $command): void
    {
        $user = $this->userRepository->findByToken($command->token);
        if (!$user) {
            throw UserNotFoundException::notFoundByToken($command->token);
        }

        $user->verify()->save($this->userRepository);
    }
}
