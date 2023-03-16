<?php

declare(strict_types=1);

namespace Quiz\UserSession\Application;

use Quiz\Shared\Domain\Bus\CommandHandler;
use Quiz\Shared\Domain\Bus\EventBus;
use Quiz\Shared\Domain\Criteria\Criteria;
use Quiz\Shared\Domain\Criteria\Filter;
use Quiz\Shared\Domain\Criteria\Filters;
use Quiz\UserSession\Domain\Email;
use Quiz\UserSession\Domain\EmailException;
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
        if ($this->userAlreadyExist($command)) {
            throw EmailException::emailAlreadyExist($command->email);
        }

        User::new($command->userId, $command->email, $command->name, $command->password)
            ->save($this->userRepository, $this->eventBus);
    }

    public function userAlreadyExist(UserCreatorCommand $command): bool
    {
        return !$this->userRepository->byCriteria($this->getEmailCriteria($command->email))->isEmpty();
    }

    public function getEmailCriteria(string $email): Criteria
    {
        return new Criteria(
            new Filters(
                new Filter(Email::class, $email)
            ),
        );
    }
}
