<?php

declare(strict_types=1);

namespace Quiz\UserSession\Application;

use Quiz\Shared\Domain\Bus\CommandHandler;
use Quiz\Shared\Domain\Bus\EventBus;
use Quiz\UserSession\Domain\User;
use Quiz\UserSession\Domain\UserRepository;

final readonly class UserCreatorCommandHandler implements CommandHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private EventBus $eventBus,
    ) {
    }

    public function dispatch(UserCreatorCommand $command): void
    {
        User::new($command->userId, $command->email, $command->name, $command->password)
            ->save($this->userRepository, $this->eventBus);
    }
}
