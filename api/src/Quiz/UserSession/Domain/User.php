<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use Quiz\Shared\Domain\Bus\EventBus;
use Quiz\Shared\Domain\Models\AggregateRoot;

final class User extends AggregateRoot
{
    public function __construct(
        private UserId $userId,
        private Email $email,
        private Name $name,
        private Password $password,
    ) {
    }

    public static function new(string $userId, string $email, string $name, string $password): User
    {
        $user = new User(
            new UserId($userId),
            new Email($email),
            new Name($name),
            new Password($password)
        );

        $user->registerNewEvent(new UserCreated($userId, $email, $name));

        return $user;
    }

    public function save(UserRepository $userRepository, EventBus $eventBus): void
    {
        $userRepository->save($this);
        $this->publishEvents($eventBus);
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function password(): Password
    {
        return $this->password;
    }
}
