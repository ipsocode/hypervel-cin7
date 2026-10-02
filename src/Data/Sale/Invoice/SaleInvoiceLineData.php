<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Data\AbstractLineData;

/**
 * Sale Invoice Line Model, also used by credit notes: the line fields of `AbstractLineData`, the
 * line `Total` the invoice table requires, the sale's `AverageCost` and the revenue `Account`.
 *
 * @see docs/data.md
 */
final class SaleInvoiceLineData extends AbstractLineData
{
    public ?float $AverageCost = null;

    #[Max(50)]
    public ?string $Account = null;

    #[Max(256)]
    public ?string $Comment = null;

    public function __construct(
        string $ProductID,
        string $SKU,
        string $Name,
        float $Quantity,
        float $Price,
        float $Tax,
        string $TaxRule,
        public float $Total,
    ) {
        parent::__construct($ProductID, $SKU, $Name, $Quantity, $Price, $Tax, $TaxRule);
    }
}
