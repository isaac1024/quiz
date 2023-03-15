<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

final readonly class Email
{
    private const EMAIL_REGEX = "/^[\w_\-.]+@([\w_-]+\.)+[\w-]{2,}$/";
    public function __construct(public string $value)
    {
        $this->validate();
    }

    private function validate(): void
    {
        if (!preg_match(self::EMAIL_REGEX, $this->value)) {
            throw EmailException::invalidEmail($this->value);
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
