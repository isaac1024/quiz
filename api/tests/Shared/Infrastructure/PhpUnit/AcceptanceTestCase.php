<?php

declare(strict_types=1);

namespace Quiz\Tests\Shared\Infrastructure\PhpUnit;

use Quiz\Shared\Domain\Models\UserId;
use Quiz\Tests\UserEmailVerification\Domain\EmailObjectMother;
use Quiz\Tests\UserEmailVerification\Domain\VerifiedObjectMother;
use Quiz\Tests\UserSession\Domain\PasswordObjectMother;
use Quiz\Tests\UserSession\Domain\UserObjectMother;
use Quiz\UserSession\Domain\UserRepository;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Messenger\Transport\InMemoryTransport;

abstract class AcceptanceTestCase extends WebTestCase
{
    private KernelBrowser $client;
    private ?string $email = null;
    protected ?string $name = null;
    private ?string $password = null;
    private ?UserId $userId = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    protected function json(string $method, string $uri, array $parameters = []): void
    {
        $this->client->jsonRequest($method, $uri, $parameters);
    }

    protected function asserStatusCode(int $statusCode): void
    {
        $response = $this->client->getResponse();
        self::assertSame($statusCode, $response->getStatusCode());
    }

    protected function getRepository(string $repositoryName)
    {
        return $this->client->getContainer()->get($repositoryName);
    }

    protected function getTransport(): InMemoryTransport
    {
        return $this->client->getContainer()->get('messenger.transport.async');
    }

    protected function login(): void
    {
        $this->json('POST', '/login', ['username' => $this->email, 'password' => $this->password]);
        $token = json_decode($this->client->getResponse()->getContent(), true)['token'] ?? null;
        if ($token) {
            $this->client->setServerParameter('HTTP_Authorization', sprintf("Bearer %s", $token));
        }
    }

    protected function createUser(): void
    {
        $password = PasswordObjectMother::raw();

        /** @var UserRepository $userRepository */
        $userRepository = $this->getRepository(UserRepository::class);
        $user = UserObjectMother::make(password: PasswordObjectMother::make($password));
        $userRepository->save($user);

        $this->userId = $user->userId();
        $this->email = $user->email()->value;
        $this->name = $user->name()->value;
        $this->password = $password;
    }

    protected function verifyUser(): void
    {
        /** @var \Quiz\UserEmailVerification\Domain\UserRepository $userRepository */
        $userRepository = $this->getRepository(\Quiz\UserEmailVerification\Domain\UserRepository::class);
        $user = $userRepository->find($this->userId);
        $userReflection = new ReflectionClass($user);
        $userReflection->getProperty('verified')->setValue($user, VerifiedObjectMother::makeVerified());

        $userRepository->save($user);
    }

    protected function password(): string
    {
        return $this->password;
    }

    protected function userId(): UserId
    {
        return $this->userId;
    }
}
