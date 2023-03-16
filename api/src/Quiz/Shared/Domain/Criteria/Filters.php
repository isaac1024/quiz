<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Criteria;

use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @template-implements IteratorAggregate<int, Filter>
 */
final readonly class Filters implements Countable, IteratorAggregate
{
    public array $filters;

    public function __construct(Filter ...$filters)
    {
        $this->filters = $filters;
    }

    public function count(): int
    {
        return count($this->filters);
    }

    public function getIterator(): Traversable
    {
        yield from $this->filters;
    }
}
