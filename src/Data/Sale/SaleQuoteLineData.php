<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Ipsocode\Cin7\Data\AbstractLineData;

/**
 * Sale Quote Line Model: the line fields of `AbstractLineData` and the sale's `AverageCost`.
 *
 * @see docs/data.md
 */
final class SaleQuoteLineData extends AbstractLineData
{
    public ?float $AverageCost = null;
}
