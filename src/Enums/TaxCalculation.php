<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * Whether a sale's or a purchase's prices include tax.
 *
 * @see docs/data.md
 */
enum TaxCalculation: string
{
    case Inclusive = 'Inclusive';
    case Exclusive = 'Exclusive';
}
