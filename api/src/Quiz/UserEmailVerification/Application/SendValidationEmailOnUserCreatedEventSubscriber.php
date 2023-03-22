<?php

declare(strict_types=1);

namespace Quiz\UserEmailVerification\Application;

use Quiz\Shared\Domain\Bus\EventSubscriber;
use Quiz\Shared\Domain\Models\UserId;
use Quiz\UserEmailVerification\Domain\EmailSender;
use Quiz\UserEmailVerification\Domain\UserNotFoundException;
use Quiz\UserEmailVerification\Domain\UserRepository;

final readonly class SendValidationEmailOnUserCreatedEventSubscriber implements EventSubscriber
{
    public function __construct(private UserRepository $userRepository, private EmailSender $emailSender)
    {
    }

    public function dispatch(SendValidationEmailOnUserCreatedEvent $event): void
    {
        $user = $this->userRepository->find(new UserId($event->aggregateId));
        if (!$user) {
            throw UserNotFoundException::notFound($event->aggregateId);
        }

        $user->sendEmail($event->email, $this->emailSender)
            ->save($this->userRepository);
    }
}
