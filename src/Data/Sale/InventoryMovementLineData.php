<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Inventory Movement Line Model.
 *
 * @see docs/data.md
 */
final class InventoryMovementLineData extends Data
{
    public function __construct(
        public string|Optional $TaskID,
        public string|Optional $ProductID,
        public string|Optional $Date,
        public float|Optional $COGS,
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
