<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use Quiz\Shared\Domain\Bus\DomainEvent;

final class UserCreated extends DomainEvent
{
    public function __construct(string $id, private readonly string $email, private readonly string $name)
    {
        parent::__construct($id);
    }

    public function type(): string
    {
        return 'quiz.user.created';
    }

    public function attributes(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
        ];
    }
}
