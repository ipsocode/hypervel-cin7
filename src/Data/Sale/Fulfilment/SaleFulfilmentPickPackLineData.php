<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Attributes\DateTime;
use Ipsocode\Cin7\Data\Concerns\HasProductFields;

/**
 * Sale Fulfilment Pick Pack Line Model. `Box` and `WarrantyRegistrationNumber` are for packing; the `Restock…` keys are for credit notes.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentPickPackLineData extends Data
{
    use HasProductFields;

    public function __construct(
        #[Uuid]
        public ?string $ProductID = null,
        #[Max(50)]
        public ?string $SKU = null,
        #[Max(1024)]
        public ?string $Name = null,
        #[Max(256)]
        public ?string $Location = null,
        #[Uuid]
        public ?string $LocationID = null,
        public ?float $Quantity = null,
        #[Max(50)]
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        #[Max(256)]
        public ?string $Box = null,
        public ?bool $NonInventory = null,
        #[Max(256)]
        public ?string $WarrantyRegistrationNumber = null,
        #[Max(256)]
        public ?string $RestockLocation = null,
        #[Uuid]
        public ?string $RestockLocationID = null,
        #[DateTime]
        public ?string $RestockDate = null,
    ) {
    }
}
