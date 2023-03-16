<?php

declare(strict_types=1);

namespace Quiz\Shared\Domain\Criteria;

final readonly class Order
{
    public function __construct(public string $field, public OrderType $orderType = OrderType::ASC)
    {
    }
}
