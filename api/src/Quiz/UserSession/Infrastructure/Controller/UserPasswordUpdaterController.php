<?php

declare(strict_types=1);

namespace Quiz\UserSession\Infrastructure\Controller;

use Quiz\Shared\Infrastructure\Symfony\ApiController;
use Quiz\UserSession\Application\UserPasswordUpdaterCommand;
use Quiz\UserSession\Domain\PasswordException;
use Quiz\UserSession\Domain\UserNotFoundException;
use Quiz\UserSession\Infrastructure\Request\UserPasswordUpdateRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class UserPasswordUpdaterController extends ApiController
{
    public function __invoke(UserPasswordUpdateRequest $request, string $userId): JsonResponse
    {
        $this->commandBus->dispatch(
            new UserPasswordUpdaterCommand($userId, $request->oldPassword, $request->newPassword)
        );

        return new JsonResponse(null, Response::HTTP_OK);
    }

    protected function mapExceptions(): array
    {
        return [
            UserNotFoundException::class => 404,
            PasswordException::class => 422,
        ];
    }
}
