<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Other;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Concerns\HasProductFields;
use Ipsocode\Cin7\Concerns\HasStockLineProductFields;

/**
 * New Stock Line Model, a line of a stock adjustment's `Lines` and `NewStockLines` or a stock
 * take's `ZeroStockOnHandProducts`: stock to add at a location. A line needs its `Quantity` and
 * `UnitCost`, its product by `ProductID` or `SKU`, and its location by `LocationID` or `Location`.
 * `BatchSN` is required when the product's costing method is not FIFO, and `ExpiryDate` for
 * `FEFO - Serial Number`; the reference writes both as "Yes*" and a rule that depends on the
 * product, so they stay optional. Like every object with a `ProductID`, it carries the product
 * fields, and the stock take's.
 *
 * @see docs/data.md
 */
final class NewStockLineData extends Data
{
    use HasProductFields;
    use HasStockLineProductFields;

    public function __construct(
        public float $Quantity,
        public float $UnitCost,
        #[RequiredWithout('SKU')]
        #[Uuid]
        public ?string $ProductID = null,
        #[RequiredWithout('ProductID')]
        #[Max(50)]
        public ?string $SKU = null,
        #[Max(1024)]
        public ?string $ProductName = null,
        #[RequiredWithout('Location')]
        #[Uuid]
        public ?string $LocationID = null,
        #[RequiredWithout('LocationID')]
        #[Max(256)]
        public ?string $Location = null,
        #[Max(50)]
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        #[DateTime]
        public ?string $ReceivedDate = null,
        #[Max(256)]
        public ?string $Comments = null,
    ) {
    }
}
