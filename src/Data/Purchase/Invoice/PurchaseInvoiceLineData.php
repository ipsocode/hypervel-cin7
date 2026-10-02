<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Invoice;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Data\AbstractLineData;

/**
 * Purchase Invoice Line Model, also used by the purchase credit notes and the advanced purchase's
 * invoices and credit notes: the line fields of `AbstractLineData`, and the `Account` and line
 * `Total` the table requires.
 *
 * @see docs/data.md
 */
final class PurchaseInvoiceLineData extends AbstractLineData
{
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
        #[Max(50)]
        public string $Account,
        public float $Total,
    ) {
        parent::__construct($ProductID, $SKU, $Name, $Quantity, $Price, $Tax, $TaxRule);
    }
}
