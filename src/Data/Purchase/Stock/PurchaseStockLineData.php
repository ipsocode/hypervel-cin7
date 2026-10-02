<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Stock;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Concerns\HasProductFields;

/**
 * Purchase Stock Line Model, a line of a simple purchase's `StockReceived` and of
 * `purchase/stock`: the items received on a date, at a location. `ProductID` and `SKU` are a bare
 * `Yes*`, so optional; `Name` and `Received` are read-only.
 *
 * @see docs/data.md
 */
final class PurchaseStockLineData extends Data
{
    use HasProductFields;

    public function __construct(
        #[DateTime]
        public string $Date,
        public float $Quantity,
        #[Uuid]
        public ?string $ProductID = null,
        #[Max(50)]
        public ?string $SKU = null,
        #[Max(1024)]
        public ?string $Name = null,
        #[Max(256)]
        #[RequiredWithout('LocationID')]
        public ?string $Location = null,
        #[Uuid]
        #[RequiredWithout('Location')]
        public ?string $LocationID = null,
        public ?bool $Received = null,
        #[Max(50)]
        public ?string $BatchSN = null,
        #[Max(50)]
        public ?string $SupplierSKU = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        #[Uuid]
        public ?string $CardID = null,
    ) {
    }
}
