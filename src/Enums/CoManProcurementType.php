<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * How a co-manufacturing work center procures: a transfer, a buy and sell, or a purchase.
 *
 * @see docs/data.md
 */
enum CoManProcurementType: string
{
    case Transfer = 'Transfer';
    case Buysell = 'Buysell';
    case Purchase = 'Purchase';
}
