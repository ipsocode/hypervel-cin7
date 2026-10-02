<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * Whether the company calculates tax on each row's total or on the document's total.
 *
 * @see docs/data.md
 */
enum TaxCalculationMethod: string
{
    case RowTotal = 'Row Total';
    case Total = 'Total';
}
