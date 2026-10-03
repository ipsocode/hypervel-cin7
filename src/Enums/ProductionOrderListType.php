<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The type of record a production order list item is: a production order or one of its runs.
 *
 * @see docs/data.md
 */
enum ProductionOrderListType: string
{
    case Order = 'O';
    case Run = 'R';
}
