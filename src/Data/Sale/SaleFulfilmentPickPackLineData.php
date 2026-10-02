<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Fulfilment Pick Pack Line Model. `Box` and `WarrantyRegistrationNumber` are for packing; the `Restock…` keys are for credit notes.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentPickPackLineData extends Data
{
    public function __construct(
        public string|Optional $ProductID,
        public string|Optional $SKU,
        public string|Optional $Name,
        public string|Optional $Location,
        public string|Optional $LocationID,
        public float|Optional $Quantity,
        public string|Optional $BatchSN,
        public string|Optional $ExpiryDate,
        public string|Optional $Box,
        public bool|Optional $NonInventory,
        public string|Optional $WarrantyRegistrationNumber,
        public string|Optional $RestockLocation,
        public string|Optional $RestockLocationID,
        public string|Optional $RestockDate,
        public float|Optional $ProductLength,
        public float|Optional $ProductWidth,
        public float|Optional $ProductHeight,
        public float|Optional $ProductWeight,
        public string|Optional $WeightUnits,
        public string|Optional $DimensionsUnits,
        public string|Optional|null $ProductCustomField1,
        public string|Optional|null $ProductCustomField2,
        public string|Optional|null $ProductCustomField3,
        public string|Optional|null $ProductCustomField4,
        public string|Optional|null $ProductCustomField5,
        public string|Optional|null $ProductCustomField6,
        public string|Optional|null $ProductCustomField7,
        public string|Optional|null $ProductCustomField8,
        public string|Optional|null $ProductCustomField9,
        public string|Optional|null $ProductCustomField10,
    ) {
    }
}
