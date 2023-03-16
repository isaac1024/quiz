<?php

declare(strict_types=1);

namespace Quiz\Shared\Infrastructure\Doctrine;

use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Quiz\Shared\Domain\Criteria\Criteria;

final class DomainCriteriaToDoctrineCriteria
{
    public static function convert(Criteria $criteria, array $fieldsMapper): DoctrineCriteria
    {
        $doctrineCriteria = DoctrineCriteria::create()
            ->setMaxResults($criteria->limit)
            ->setFirstResult($criteria->offset);

        foreach ($criteria->filters as $filter) {
            $filterField = $fieldsMapper[$filter->field];
            $doctrineCriteria->andWhere(new Comparison($filterField, $filter->operator->value, $filter->value));
        }

        if ($criteria->order) {
            $doctrineCriteria->orderBy([
                $fieldsMapper[$criteria->order->field] => $criteria->order->orderType->value,
            ]);
        }

        return $doctrineCriteria;
    }
}
