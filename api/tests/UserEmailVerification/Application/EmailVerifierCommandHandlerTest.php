<?php

namespace Quiz\Tests\UserEmailVerification\Application;

use PHPUnit\Framework\MockObject\MockObject;
use Quiz\Shared\Domain\Models\DateTimeUtils;
use Quiz\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use Quiz\Tests\UserEmailVerification\Domain\UserObjectMother;
use Quiz\Tests\UserEmailVerification\Domain\VerifiedObjectMother;
use Quiz\UserEmailVerification\Application\EmailVerifierCommandHandler;
use Quiz\UserEmailVerification\Domain\UserNotFoundException;
use Quiz\UserEmailVerification\Domain\UserRepository;
use Quiz\UserEmailVerification\Domain\UserTokenExpiredException;

class EmailVerifierCommandHandlerTest extends UnitTestCase
{
    private EmailVerifierCommandHandler $emailVerifierCommandHandler;
    private UserRepository&MockObject $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->getMockBuilder(UserRepository::class)->getMock();
        $this->emailVerifierCommandHandler = new EmailVerifierCommandHandler($this->userRepository);
    }

    public function testVerifyUser(): void
    {
        $command = EmailVerifierCommandObjectMother::make();
        $user = UserObjectMother::make(verified: VerifiedObjectMother::makeNotVerified(
            $command->token,
            DateTimeUtils::fromRelative('+15 min')
        ));

        $this->userRepository->expects($this->once())
            ->method('findByToken')
            ->with($command->token)
            ->willReturn($user);

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($user);

        $this->emailVerifierCommandHandler->dispatch($command);
    }

    public function testVerifyUserNotFoundUserShouldFail(): void
    {
        $command = EmailVerifierCommandObjectMother::make();
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage(sprintf("User not found with token: %s.", $command->token));

        $this->userRepository->expects($this->once())
            ->method('findByToken')
            ->with($command->token)
            ->willReturn(null);

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->emailVerifierCommandHandler->dispatch($command);
    }

    public function testVerifyUserWithExpiredTokenShouldFail(): void
    {
        $command = EmailVerifierCommandObjectMother::make();
        $user = UserObjectMother::make(verified: VerifiedObjectMother::makeNotVerified(
            $command->token,
            DateTimeUtils::fromRelative('-15 min')
        ));
        $this->expectException(UserTokenExpiredException::class);
        $this->expectExceptionMessage('Email verification token expired.');

        $this->userRepository->expects($this->once())
            ->method('findByToken')
            ->with($command->token)
            ->willReturn($user);

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->emailVerifierCommandHandler->dispatch($command);
    }

    public function testVerifyUserNotFoundUser(): void
    {
        $command = EmailVerifierCommandObjectMother::make();
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage(sprintf("User not found with token: %s.", $command->token));

        $this->userRepository->expects($this->once())
            ->method('findByToken')
            ->with($command->token)
            ->willReturn(null);

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->emailVerifierCommandHandler->dispatch($command);
    }
}
