<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Other;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Concerns\HasProductFields;
use Ipsocode\Cin7\Concerns\HasStockLineProductFields;

/**
 * Existing Stock Line Model, a line of a stock adjustment's `ExistingStockLines` or a stock take's
 * `NonZeroStockOnHandProducts`: stock already on hand and the `Adjustment` to it. It is in
 * responses only: nothing in it is required, and the write bodies send `NewStockLineData`.
 * Like every object with a `ProductID`, it carries the product fields, and the stock take's.
 *
 * @see docs/data.md
 */
final class ExistingStockLineData extends Data
{
    use HasProductFields;
    use HasStockLineProductFields;

    public function __construct(
        #[Uuid]
        public ?string $ProductID = null,
        #[Max(50)]
        public ?string $SKU = null,
        #[Max(1024)]
        public ?string $ProductName = null,
        public ?float $QuantityOnHand = null,
        public ?float $Available = null,
        public ?float $Adjustment = null,
        #[Uuid]
        public ?string $LocationID = null,
        #[Max(256)]
        public ?string $Location = null,
        #[Max(50)]
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        #[Max(256)]
        public ?string $Comments = null,
    ) {
    }
}
