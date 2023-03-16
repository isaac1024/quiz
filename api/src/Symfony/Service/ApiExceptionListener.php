<?php

declare(strict_types=1);

namespace App\Service;

use Quiz\Shared\Infrastructure\Symfony\ExceptionToHttpStatusCode;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;

final readonly class ApiExceptionListener
{
    public function __construct(private ExceptionToHttpStatusCode $exceptionToHttpStatusCode)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $event->setResponse($this->getJsonResponse($exception));
    }

    private function getJsonResponse(Throwable $exception): JsonResponse
    {
        return new JsonResponse(
            ['code' => $exception->errorCode ?? null, 'message' => $exception->getMessage()],
            $this->exceptionToHttpStatusCode->getStatusCode($exception::class)
        );
    }
}
