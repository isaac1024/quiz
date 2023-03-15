<?php

declare(strict_types=1);

namespace TestsShared;

use Quiz\Shared\Domain\Bus\Event;
use ReflectionClass;
use SebastianBergmann\Comparator\Comparator;
use SebastianBergmann\Comparator\ComparisonFailure;

final class EventComparator extends Comparator
{
    private const IGNORE_PROPERTIES = ['eventId', 'occurredOn'];

    public function accepts($expected, $actual): bool
    {
        return $expected instanceof Event && $actual instanceof Event;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function assertEquals($expected, $actual, $delta = 0.0, $canonicalize = false, $ignoreCase = false): void
    {
        $this->recursiveComparator($expected, $actual);
    }

    public function recursiveComparator($expected, $actual): void
    {
        if ($actual::class !== $expected::class) {
            $expectedExported = $this->exporter->export($expected);
            $actualExported = $this->exporter->export($actual);
            throw new ComparisonFailure(
                $expected,
                $actual,
                $expectedExported,
                $actualExported,
                false,
                sprintf('%s is not instance of expected class "%s".', $actualExported, $expected::class),
            );
        }

        $reflectionClass = new ReflectionClass($expected);

        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if (in_array($reflectionProperty->getName(), self::IGNORE_PROPERTIES, true)) {
                continue;
            }

            $reflectionProperty->setAccessible(true);

            $expectedValue = $reflectionProperty->getValue($expected);
            $actualValue = $reflectionProperty->getValue($actual);

            if (is_object($expectedValue)) {
                $this->recursiveComparator($expectedValue, $actualValue);
                continue;
            }

            if ($expectedValue !== $actualValue) {
                throw new ComparisonFailure(
                    $expected,
                    $actual,
                    $this->exporter->export($expected),
                    $this->exporter->export($actual),
                    false,
                    'Failed asserting that two objects are equal.',
                );
            }
        }
    }
}
