<?php

declare(strict_types=1);

namespace Quiz\Tests\UserSession\Application;

use Faker\Factory;
use PHPUnit\Framework\MockObject\MockObject;
use Quiz\Shared\Domain\Bus\EventBus;
use Quiz\Shared\Domain\Criteria\Criteria;
use Quiz\Shared\Domain\Criteria\Filter;
use Quiz\Shared\Domain\Criteria\Filters;
use Quiz\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use Quiz\Tests\UserSession\Domain\UserCreatedObjectMother;
use Quiz\Tests\UserSession\Domain\UserObjectMother;
use Quiz\UserSession\Application\UserCreatorCommandHandler;
use Quiz\UserSession\Domain\Email;
use Quiz\UserSession\Domain\EmailException;
use Quiz\UserSession\Domain\NameException;
use Quiz\UserSession\Domain\PasswordException;
use Quiz\UserSession\Domain\UserCollection;
use Quiz\UserSession\Domain\UserIdException;
use Quiz\UserSession\Domain\UserRepository;

class UserCreatorCommandHandlerTest extends UnitTestCase
{
    private UserRepository&MockObject $userRepository;
    private EventBus&MockObject $eventBus;
    private UserCreatorCommandHandler $userCreatorCommandHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->getMockBuilder(UserRepository::class)->getMock();
        $this->eventBus = $this->getMockBuilder(EventBus::class)->getMock();
        $this->userCreatorCommandHandler = new UserCreatorCommandHandler($this->userRepository, $this->eventBus);
    }

    public function testCreateUser(): void
    {
        $userCreatorCommand = UserCreatorCommandObjectMother::make();
        $user = UserObjectMother::fromUserCreatorCommand($userCreatorCommand);
        $userCreated = UserCreatedObjectMother::fromUser($user);
        $emailCriteria = new Criteria(
            new Filters(
                new Filter(Email::class, $userCreatorCommand->email)
            ),
        );

        $this->userRepository->expects($this->once())
            ->method('byCriteria')
            ->with($emailCriteria)
            ->willReturn(new UserCollection());

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($user);

        $this->eventBus->expects($this->once())
            ->method('publish')
            ->with($userCreated);

        $this->userCreatorCommandHandler->dispatch($userCreatorCommand);
    }

    public function testCreateUserWithInvalidUserIdShouldFail(): void
    {
        $invalidUuid = 'invalid_uuid';
        $userCreatorCommand = UserCreatorCommandObjectMother::make($invalidUuid);
        $this->expectException(UserIdException::class);
        $this->expectExceptionMessage(sprintf("User id %s is not a valid uuid.", $invalidUuid));
        $emailCriteria = new Criteria(
            new Filters(
                new Filter(Email::class, $userCreatorCommand->email)
            ),
        );

        $this->userRepository->expects($this->once())
            ->method('byCriteria')
            ->with($emailCriteria)
            ->willReturn(new UserCollection());

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->eventBus->expects($this->never())
            ->method('publish');

        $this->userCreatorCommandHandler->dispatch($userCreatorCommand);
    }

    public function testCreateUserWithInvalidEmailShouldFail(): void
    {
        $invalidEmail = 'invalid_email';
        $userCreatorCommand = UserCreatorCommandObjectMother::make(email: $invalidEmail);
        $this->expectException(EmailException::class);
        $this->expectExceptionMessage(sprintf("Email %s is not valid.", $invalidEmail));
        $emailCriteria = new Criteria(
            new Filters(
                new Filter(Email::class, $userCreatorCommand->email)
            ),
        );

        $this->userRepository->expects($this->once())
            ->method('byCriteria')
            ->with($emailCriteria)
            ->willReturn(new UserCollection());

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->eventBus->expects($this->never())
            ->method('publish');

        $this->userCreatorCommandHandler->dispatch($userCreatorCommand);
    }

    public function testCreateUserWithEmptyNameShouldFail(): void
    {
        $emptyName = '';
        $userCreatorCommand = UserCreatorCommandObjectMother::make(name: $emptyName);
        $this->expectException(NameException::class);
        $this->expectExceptionMessage("Name is empty.");
        $emailCriteria = new Criteria(
            new Filters(
                new Filter(Email::class, $userCreatorCommand->email)
            ),
        );

        $this->userRepository->expects($this->once())
            ->method('byCriteria')
            ->with($emailCriteria)
            ->willReturn(new UserCollection());

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->eventBus->expects($this->never())
            ->method('publish');

        $this->userCreatorCommandHandler->dispatch($userCreatorCommand);
    }

    public function testCreateUserWithOnlyWhitespacesNameShouldFail(): void
    {
        $onlyWhitespacesName = ' ';
        $userCreatorCommand = UserCreatorCommandObjectMother::make(name: $onlyWhitespacesName);
        $this->expectException(NameException::class);
        $this->expectExceptionMessage(sprintf("Name '%s' contain whitespaces at first or end.", $onlyWhitespacesName));
        $emailCriteria = new Criteria(
            new Filters(
                new Filter(Email::class, $userCreatorCommand->email)
            ),
        );

        $this->userRepository->expects($this->once())
            ->method('byCriteria')
            ->with($emailCriteria)
            ->willReturn(new UserCollection());

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->eventBus->expects($this->never())
            ->method('publish');

        $this->userCreatorCommandHandler->dispatch($userCreatorCommand);
    }

    public function testCreateUserWithShortPasswordShouldFail(): void
    {
        $faker = Factory::create();
        $shortPassword = $faker->password(0, 11);
        $userCreatorCommand = UserCreatorCommandObjectMother::make(password: $shortPassword);
        $this->expectException(PasswordException::class);
        $this->expectExceptionMessage(sprintf("Password %s is too short. Min length %d.", $shortPassword, 12));
        $emailCriteria = new Criteria(
            new Filters(
                new Filter(Email::class, $userCreatorCommand->email)
            ),
        );

        $this->userRepository->expects($this->once())
            ->method('byCriteria')
            ->with($emailCriteria)
            ->willReturn(new UserCollection());

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->eventBus->expects($this->never())
            ->method('publish');

        $this->userCreatorCommandHandler->dispatch($userCreatorCommand);
    }

    public function testCreateUserWithDuplicateEmailShouldFail(): void
    {
        $userCreatorCommand = UserCreatorCommandObjectMother::make();
        $user = UserObjectMother::fromUserCreatorCommand($userCreatorCommand);
        $this->expectException(EmailException::class);
        $this->expectExceptionMessage(sprintf("Already exist user with email %s.", $userCreatorCommand->email));
        $emailCriteria = new Criteria(
            new Filters(
                new Filter(Email::class, $userCreatorCommand->email)
            ),
        );

        $this->userRepository->expects($this->once())
            ->method('byCriteria')
            ->with($emailCriteria)
            ->willReturn(new UserCollection($user));

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->eventBus->expects($this->never())
            ->method('publish');

        $this->userCreatorCommandHandler->dispatch($userCreatorCommand);
    }
}
