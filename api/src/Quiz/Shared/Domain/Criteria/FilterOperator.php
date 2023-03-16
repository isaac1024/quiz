<?php

namespace Quiz\Shared\Domain\Criteria;

enum FilterOperator: string
{
    case EQ = '=';
    case NEQ = '!=';
    case GT = '>';
    case GTE = '>=';
    case LT = '<';
    case LTE = '<=';
}
