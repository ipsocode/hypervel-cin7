<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Order;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Data\AbstractLineData;

/**
 * Purchase Order Line Model, also a line of the purchase and the advanced purchase's `Order`: the
 * line fields of `AbstractLineData`, the line `Total` the table requires, the `SupplierSKU` and a
 * `Comment`.
 *
 * @see docs/data.md
 */
final class PurchaseOrderLineData extends AbstractLineData
{
    #[Max(50)]
    public ?string $SupplierSKU = null;

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
