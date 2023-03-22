<?php

declare(strict_types=1);

namespace Quiz\UserEmailVerification\Infrastructure\Controller;

use Quiz\Shared\Domain\Bus\CommandBus;
use Quiz\Shared\Infrastructure\Symfony\ApiController;
use Quiz\Shared\Infrastructure\Symfony\ExceptionToHttpStatusCode;
use Quiz\UserEmailVerification\Application\EmailVerifierCommand;
use Quiz\UserEmailVerification\Domain\UserNotFoundException;
use Quiz\UserEmailVerification\Domain\UserTokenExpiredException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class EmailVerificationController extends ApiController
{
    public function __construct(
        CommandBus $commandBus,
        ExceptionToHttpStatusCode $exceptionToHttpStatusCode,
        private string $appHome,
    ) {
        parent::__construct($commandBus, $exceptionToHttpStatusCode);
    }

    public function __invoke(string $token): RedirectResponse
    {
        $this->commandBus->dispatch(new EmailVerifierCommand($token));

        return new RedirectResponse($this->appHome, Response::HTTP_FOUND);
    }

    protected function mapExceptions(): array
    {
        return [
            UserNotFoundException::class => 404,
            UserTokenExpiredException::class => 401,
        ];
    }
}
