<?php

declare(strict_types=1);

namespace Quiz\UserSession\Domain;

use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @template-implements IteratorAggregate<int, User>
 */
final readonly class UserCollection implements Countable, IteratorAggregate
{
    public array $users;

    public function __construct(User ...$users)
    {
        $this->users = $users;
    }

    public function count(): int
    {
        return count($this->users);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function getIterator(): Traversable
    {
        yield from $this->users;
    }
}
