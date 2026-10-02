<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
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
        public ?string $ProductID = null,
        public ?string $SKU = null,
        public ?string $Name = null,
        public ?string $Location = null,
        public ?string $LocationID = null,
        public ?float $Quantity = null,
        public ?string $BatchSN = null,
        public ?string $ExpiryDate = null,
        public ?string $Box = null,
        public ?bool $NonInventory = null,
        public ?string $WarrantyRegistrationNumber = null,
        public ?string $RestockLocation = null,
        public ?string $RestockLocationID = null,
        public ?string $RestockDate = null,
    ) {
    }
}
