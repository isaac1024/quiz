<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain;

use DomainException;

abstract class QuizException extends DomainException
{
    public function __construct(public readonly string $errorCode, string $message)
    {
        parent::__construct($message);
    }
}
