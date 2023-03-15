<?php

namespace App\Controller;

use App\Request\UserCreatorRequest;
use Quiz\Shared\Domain\Bus\CommandBus;
use Quiz\Shared\Domain\Models\UuidUtils;
use Quiz\UserSession\Application\UserCreatorCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class UserCreatorController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    #[Route('/register', name: 'app_user_creator')]
    public function __invoke(UserCreatorRequest $request): JsonResponse
    {
        $this->commandBus->dispatch(new UserCreatorCommand(
            UuidUtils::random(),
            $request->email,
            $request->name,
            $request->password,
        ));
        return $this->json([], JsonResponse::HTTP_CREATED);
    }
}
