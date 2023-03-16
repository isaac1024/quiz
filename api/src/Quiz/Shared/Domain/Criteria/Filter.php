<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Criteria;

final readonly class Filter
{
    public function __construct(
        public string $field,
        public mixed $value,
        public FilterOperator $operator = FilterOperator::EQ
    ) {
    }
}
