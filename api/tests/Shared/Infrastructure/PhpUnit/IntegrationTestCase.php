<?php

declare(strict_types=1);

namespace Quiz\Tests\Shared\Infrastructure\PhpUnit;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class IntegrationTestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
    }

    protected function get(string $className)
    {
        return $this->getContainer()->get($className);
    }
}
