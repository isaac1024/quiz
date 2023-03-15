<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Models;

abstract readonly class Uuid
{
    public function __construct(public string $value)
    {
        $this->validate();
    }

    private function validate(): void
    {
        if (!UuidUtils::isValid($this->value)) {
            $this->throwException();
        }
    }

    abstract protected function throwException(): never;

    public function __toString(): string
    {
        return $this->value;
    }
}
