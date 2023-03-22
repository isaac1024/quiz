<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

final readonly class Email
{
    private const EMAIL_REGEX = "/^[\w_\-.]+@([\w_-]+\.)+[\w-]{2,}$/";
    private const MAX_LENGTH = 180;

    public function __construct(public string $value)
    {
        $this->validate();
    }

    private function validate(): void
    {
        if (!preg_match(self::EMAIL_REGEX, $this->value)) {
            throw EmailException::invalidEmail($this->value);
        }

        if (strlen($this->value) > self::MAX_LENGTH) {
            throw EmailException::tooLong($this->value, self::MAX_LENGTH);
        }
    }
}
