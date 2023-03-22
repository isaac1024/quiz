<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Application;

use Faker\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use Quiz\Shared\Domain\Bus\EventBus;
use Quiz\Tests\Shared\Domain\Models\UserIdObjectMother;
use Quiz\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use Quiz\Tests\UserSession\Domain\PasswordObjectMother;
use Quiz\Tests\UserSession\Domain\UserObjectMother;
use Quiz\Tests\UserSession\Domain\UserPasswordUpdatedObjectMother;
use Quiz\UserSession\Application\UserPasswordUpdaterCommandHandler;
use Quiz\UserSession\Domain\PasswordException;
use Quiz\UserSession\Domain\UserNotFoundException;
use Quiz\UserSession\Domain\UserRepository;

class UserPasswordUpdaterCommandHandlerTest extends UnitTestCase
{
    private UserRepository&MockObject $userRepository;
    private EventBus&MockObject $eventBus;
    private UserPasswordUpdaterCommandHandler $userPasswordUpdaterCommandHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository ??= $this->getMockBuilder(UserRepository::class)->getMock();
        $this->eventBus ??= $this->getMockBuilder(EventBus::class)->getMock();
        $this->userPasswordUpdaterCommandHandler ??= new UserPasswordUpdaterCommandHandler(
            $this->userRepository,
            $this->eventBus
        );
    }

    public function testUpdateUserPassword(): void
    {
        $userPasswordUpdaterCommand = UserPasswordUpdaterCommandObjectMother::make();
        $user = UserObjectMother::make(
            userId: UserIdObjectMother::make($userPasswordUpdaterCommand->userId),
            password: PasswordObjectMother::make($userPasswordUpdaterCommand->oldPassword)
        );
        $userPasswordUpdated = UserPasswordUpdatedObjectMother::make($user->userId()->value);

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with($user->userId())
            ->willReturn($user);

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($user);

        $this->eventBus->expects($this->once())
            ->method('publish')
            ->with($userPasswordUpdated);

        $this->userPasswordUpdaterCommandHandler->dispatch($userPasswordUpdaterCommand);
    }

    public function testUpdateUserWithShortNewPasswordShouldFail(): void
    {
        $faker = Factory::create();
        $shortPassword = $faker->password(0, 11);
        $userPasswordUpdaterCommand = UserPasswordUpdaterCommandObjectMother::make(newPassword: $shortPassword);
        $user = UserObjectMother::make(
            userId: UserIdObjectMother::make($userPasswordUpdaterCommand->userId),
            password: PasswordObjectMother::make($userPasswordUpdaterCommand->oldPassword)
        );
        $this->expectException(PasswordException::class);
        $this->expectExceptionMessage(sprintf("Password is too short. Min length %d.", 12));

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with($user->userId())
            ->willReturn($user);

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->eventBus->expects($this->never())
            ->method('publish');

        $this->userPasswordUpdaterCommandHandler->dispatch($userPasswordUpdaterCommand);
    }

    public function testUpdateUserWithInvalidOldPasswordShouldFail(): void
    {
        $faker = Factory::create();
        $password = $faker->password(12);
        $user = UserObjectMother::make();
        $userPasswordUpdaterCommand = UserPasswordUpdaterCommandObjectMother::make($user->userId()->value, $password);
        $this->expectException(PasswordException::class);
        $this->expectExceptionMessage("Current password is not correct.");

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with($user->userId())
            ->willReturn($user);

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->eventBus->expects($this->never())
            ->method('publish');

        $this->userPasswordUpdaterCommandHandler->dispatch($userPasswordUpdaterCommand);
    }

    public function testUpdateUserWithSamePasswordShouldFail(): void
    {
        $password = PasswordObjectMother::raw();
        $userPasswordUpdaterCommand = UserPasswordUpdaterCommandObjectMother::make(
            oldPassword: $password,
            newPassword: $password
        );
        $user = UserObjectMother::make(
            userId: UserIdObjectMother::make($userPasswordUpdaterCommand->userId),
            password: PasswordObjectMother::make($userPasswordUpdaterCommand->oldPassword)
        );
        $this->expectException(PasswordException::class);
        $this->expectExceptionMessage("New password is same.");

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with($user->userId())
            ->willReturn($user);

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->eventBus->expects($this->never())
            ->method('publish');

        $this->userPasswordUpdaterCommandHandler->dispatch($userPasswordUpdaterCommand);
    }

    public function testNotFoundUserShouldFail(): void
    {
        $userPasswordUpdaterCommand = UserPasswordUpdaterCommandObjectMother::make();
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage(sprintf("User %s not found.", $userPasswordUpdaterCommand->userId));

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with($userPasswordUpdaterCommand->userId)
            ->willReturn(null);

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->eventBus->expects($this->never())
            ->method('publish');

        $this->userPasswordUpdaterCommandHandler->dispatch($userPasswordUpdaterCommand);
    }
}
