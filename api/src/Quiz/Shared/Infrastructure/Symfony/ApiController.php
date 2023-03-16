<?php

declare(strict_types=1);

namespace Quiz\Shared\Infrastructure\Symfony;

use Quiz\Shared\Domain\Bus\CommandBus;

abstract readonly class ApiController
{
    public function __construct(
        protected CommandBus $commandBus,
        ExceptionToHttpStatusCode $exceptionToHttpStatusCode
    ) {
        foreach ($this->mapExceptions() as $exceptionClass => $statusCode) {
            $exceptionToHttpStatusCode->map($exceptionClass, $statusCode);
        }
    }

    abstract protected function mapExceptions(): array;
}
