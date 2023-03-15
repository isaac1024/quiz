<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

final readonly class Name
{
    public function __construct(public string $value)
    {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->value !== trim($this->value)) {
            throw NameException::nameWithWhitespaces($this->value);
        }

        if (empty($this->value)) {
            throw NameException::emptyName();
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
