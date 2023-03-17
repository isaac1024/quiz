<?php

declare(strict_types=1);

namespace App\Service;

use Quiz\Shared\Infrastructure\Symfony\ExceptionToHttpStatusCode;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
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
        $e = $exception;
        while ($e instanceof HandlerFailedException) {
            $e = $e->getPrevious();
        }

        return new JsonResponse(
            ['code' => $e->errorCode ?? null, 'message' => $e->getMessage()],
            $this->exceptionToHttpStatusCode->getStatusCode($e::class)
        );
    }
}
