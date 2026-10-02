<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Quote;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Data\AbstractLineData;

/**
 * Sale Quote Line Model: the line fields of `AbstractLineData`, the `AverageCost` and `Comment`
 * the quote table alone requires, and the line `Total`.
 *
 * @see docs/data.md
 */
final class SaleQuoteLineData extends AbstractLineData
{
    public ?float $Total = null;

    public function __construct(
        string $ProductID,
        string $SKU,
        string $Name,
        float $Quantity,
        float $Price,
        float $Tax,
        string $TaxRule,
        public float $AverageCost,
        #[Max(256)]
        public string $Comment,
    ) {
        parent::__construct($ProductID, $SKU, $Name, $Quantity, $Price, $Tax, $TaxRule);
    }
}
