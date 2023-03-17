<?php

declare(strict_types=1);

namespace Quiz\Tests\Shared\Infrastructure\PhpUnit;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AcceptanceTestCase extends WebTestCase
{
    private KernelBrowser $client;

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

    protected function login(string $username, string $password): void
    {
        $this->json('POST', '/login', ['username' => $username, 'password' => $password]);
        $token = json_decode($this->client->getResponse()->getContent(), true)['token'] ?? null;
        if ($token) {
            $this->client->setServerParameter('HTTP_Authorization', sprintf("Bearer %s", $token));
        }
    }
}
