<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Criteria;

final readonly class Criteria
{
    public function __construct(
        public Filters $filters,
        public ?Order $order = null,
        public ?int $limit = null,
        public ?int $offset = null
    ) {
    }
}
