<?php

declare(strict_types=1);

namespace Quiz\UserSession\Application;

use Quiz\Shared\Domain\Bus\CommandHandler;
use Quiz\Shared\Domain\Bus\EventBus;
use Quiz\UserSession\Domain\UserId;
use Quiz\UserSession\Domain\UserNotFoundException;
use Quiz\UserSession\Domain\UserRepository;

final readonly class UserPasswordUpdaterCommandHandler implements CommandHandler
{
    public function __construct(private UserRepository $userRepository, private EventBus $eventBus)
    {
    }

    public function dispatch(UserPasswordUpdaterCommand $command): void
    {
        $user = $this->userRepository->find(new UserId($command->userId));
        if ($user === null) {
            throw UserNotFoundException::notFound($command->userId);
        }

        $user->updatePassword($command->oldPassword, $command->newPassword)
            ->save($this->userRepository, $this->eventBus);
    }
}
