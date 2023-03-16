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
}
