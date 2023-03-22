<?php

declare(strict_types=1);

namespace Quiz\UserEmailVerification\Domain;

use Quiz\Shared\Domain\Models\AggregateRoot;
use Quiz\Shared\Domain\Models\UserId;

final class User extends AggregateRoot
{
    public function __construct(
        private UserId $userId,
        private Email $email,
        private string $name,
        private Verified $verified,
    ) {
    }

    public function sendEmail(string $email, EmailSender $emailSender): User
    {
        if (!$this->email->isSame($email)) {
            return $this;
        }

        if ($this->verified->isVerified) {
            return $this;
        }

        $this->verified = $this->verified->generateToken();

        $emailSender->send($this);

        return $this;
    }

    public function verify(): User
    {
        if ($this->verified->isVerified) {
            return $this;
        }

        if ($this->verified->isExpired()) {
            throw UserTokenExpiredException::expired();
        }

        $this->verified = $this->verified->verify();

        return $this;
    }

    public function save(UserRepository $userRepository): void
    {
        $userRepository->save($this);
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function verified(): Verified
    {
        return $this->verified;
    }
}
