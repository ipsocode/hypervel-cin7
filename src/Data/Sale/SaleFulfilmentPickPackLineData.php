<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * Sale Fulfilment Pick Pack Line Model. `Box` and `WarrantyRegistrationNumber` are for packing; the `Restock…` keys are for credit notes.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentPickPackLineData extends Data
{
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
        public ?float $ProductLength = null,
        public ?float $ProductWidth = null,
        public ?float $ProductHeight = null,
        public ?float $ProductWeight = null,
        public ?string $WeightUnits = null,
        public ?string $DimensionsUnits = null,
        public ?string $ProductCustomField1 = null,
        public ?string $ProductCustomField2 = null,
        public ?string $ProductCustomField3 = null,
        public ?string $ProductCustomField4 = null,
        public ?string $ProductCustomField5 = null,
        public ?string $ProductCustomField6 = null,
        public ?string $ProductCustomField7 = null,
        public ?string $ProductCustomField8 = null,
        public ?string $ProductCustomField9 = null,
        public ?string $ProductCustomField10 = null,
    ) {
    }
}
