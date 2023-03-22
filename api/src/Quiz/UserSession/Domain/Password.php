<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

final readonly class Password
{
    private const MIN_CHARACTERS = 12;

    public readonly string $value;

    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $this->hashPassword($value);
    }

    private function validate(string $password): void
    {
        if (strlen($password) < self::MIN_CHARACTERS) {
            throw PasswordException::tooShort(self::MIN_CHARACTERS);
        }
    }

    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID);
    }

    public function update(string $old, string $new): Password
    {
        if (!password_verify($old, $this->value)) {
            throw PasswordException::notMatch();
        }

        if ($old === $new) {
            throw PasswordException::notChanged();
        }

        return new Password($new);
    }
}
