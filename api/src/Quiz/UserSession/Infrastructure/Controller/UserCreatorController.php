<?php

namespace Quiz\UserSession\Infrastructure\Controller;

use Quiz\Shared\Domain\Models\UuidUtils;
use Quiz\Shared\Infrastructure\Symfony\ApiController;
use Quiz\UserSession\Application\UserCreatorCommand;
use Quiz\UserSession\Domain\EmailException;
use Quiz\UserSession\Domain\NameException;
use Quiz\UserSession\Domain\PasswordException;
use Quiz\UserSession\Domain\UserIdException;
use Quiz\UserSession\Infrastructure\Request\UserCreatorRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class UserCreatorController extends ApiController
{
    public function __invoke(UserCreatorRequest $request): JsonResponse
    {
        $id = UuidUtils::random();
        $this->commandBus->dispatch(new UserCreatorCommand(
            $id,
            $request->email,
            $request->name,
            $request->password,
        ));

        return new JsonResponse(['id' => $id], Response::HTTP_CREATED);
    }

    protected function mapExceptions(): array
    {
        return [
            EmailException::class => 422,
            NameException::class => 422,
            PasswordException::class => 422,
            UserIdException::class => 422,
        ];
    }
}
