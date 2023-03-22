<?php

declare(strict_types=1);

namespace Quiz\Tests\UserEmailVerification\Application;

use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use Quiz\Tests\Shared\Domain\Models\UserIdObjectMother;
use Quiz\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use Quiz\Tests\UserEmailVerification\Domain\EmailObjectMother;
use Quiz\Tests\UserEmailVerification\Domain\UserObjectMother;
use Quiz\Tests\UserEmailVerification\Domain\VerifiedObjectMother;
use Quiz\UserEmailVerification\Application\SendValidationEmailOnUserCreatedEventSubscriber;
use Quiz\UserEmailVerification\Domain\EmailSender;
use Quiz\UserEmailVerification\Domain\UserNotFoundException;
use Quiz\UserEmailVerification\Domain\UserRepository;

class SendValidationEmailOnUserCreatedEventSubscriberTest extends UnitTestCase
{
    private SendValidationEmailOnUserCreatedEventSubscriber $sendValidationEmailOnUserCreatedEventSubscriber;
    private UserRepository&MockObject $userRepository;
    private EmailSender&MockObject $emailSender;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->getMockBuilder(UserRepository::class)->getMock();
        $this->emailSender = $this->getMockBuilder(EmailSender::class)->getMock();
        $this->sendValidationEmailOnUserCreatedEventSubscriber = new SendValidationEmailOnUserCreatedEventSubscriber(
            $this->userRepository,
            $this->emailSender
        );
    }

    public function testSendVerificationEmail(): void
    {
        $event = SendValidationEmailOnUserCreatedEventObjectMother::make();
        $user = UserObjectMother::make(
            UserIdObjectMother::make($event->aggregateId),
            EmailObjectMother::make($event->email),
        );

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with($user->userId())
            ->willReturn($user);

        $this->emailSender->expects($this->once())
            ->method('send')
            ->with($user);

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($user);

        $this->sendValidationEmailOnUserCreatedEventSubscriber->dispatch($event);

        $this->assertFalse($user->verified()->isVerified);
        $this->assertMatchesRegularExpression('/[a-f0-9]{128}/', $user->verified()->token);
        $this->assertInstanceOf(DateTimeImmutable::class, $user->verified()->expiration);
    }

    public function testNotSendVerificationEmailWhenEmailChange(): void
    {
        $event = SendValidationEmailOnUserCreatedEventObjectMother::make();
        $user = UserObjectMother::make(
            UserIdObjectMother::make($event->aggregateId),
        );

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with($user->userId())
            ->willReturn($user);

        $this->emailSender->expects($this->never())
            ->method('send');

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($user);

        $this->sendValidationEmailOnUserCreatedEventSubscriber->dispatch($event);

        $this->assertFalse($user->verified()->isVerified);
        $this->assertNull($user->verified()->token);
        $this->assertNull($user->verified()->expiration);
    }

    public function testNotSendVerificationEmailWhenEmailIsVerified(): void
    {
        $event = SendValidationEmailOnUserCreatedEventObjectMother::make();
        $user = UserObjectMother::make(
            userId: UserIdObjectMother::make($event->aggregateId),
            email: EmailObjectMother::make($event->email),
            verified: VerifiedObjectMother::makeVerified()
        );

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with($user->userId())
            ->willReturn($user);

        $this->emailSender->expects($this->never())
            ->method('send');

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($user);

        $this->sendValidationEmailOnUserCreatedEventSubscriber->dispatch($event);

        $this->assertTrue($user->verified()->isVerified);
        $this->assertNull($user->verified()->token);
        $this->assertNull($user->verified()->expiration);
    }

    public function testWhenUserNotFoundShouldFail(): void
    {
        $event = SendValidationEmailOnUserCreatedEventObjectMother::make();
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage(sprintf("User %s not found.", $event->aggregateId));

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with(UserIdObjectMother::make($event->aggregateId))
            ->willReturn(null);

        $this->emailSender->expects($this->never())
            ->method('send');

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->sendValidationEmailOnUserCreatedEventSubscriber->dispatch($event);
    }
}
