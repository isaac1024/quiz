<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Bundle\SecurityBundle\Security;

final class JWTCreatedListener
{
    public function __construct(private readonly Security $security)
    {
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $this->getUser();
        $payload = $this->getPayload($user);
        $event->setData($payload);
    }

    private function getUser(): User
    {
        return $this->security->getUser();
    }

    private function getPayload(User $user): array
    {
        return [
            'sub' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}
