<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * Inventory Movement Line Model.
 *
 * @see docs/data.md
 */
final class InventoryMovementLineData extends Data
{
    public function __construct(
        public ?string $TaskID = null,
        public ?string $ProductID = null,
        public ?string $Date = null,
        public ?float $COGS = null,
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
