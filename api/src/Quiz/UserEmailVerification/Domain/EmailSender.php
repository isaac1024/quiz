<?php

declare(strict_types=1);

namespace Quiz\UserEmailVerification\Domain;

interface EmailSender
{
    public function send(User $user): void;
}
