<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\PutAway;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * Advanced Purchase Put Away Line Model, a line of an advanced purchase's put away. `ProductID`
 * and `SKU` are a bare `Yes*`, so optional; a line needs its `Location` or its `LocationID`.
 * `Name` and `Received` are read-only, and the write request leaves them out of the body.
 *
 * @see docs/data.md
 */
final class AdvancedPurchasePutAwayLineData extends Data
{
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
