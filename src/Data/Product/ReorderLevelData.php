<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Data;

/**
 * Reorder Level Model, one entry of a product's `ReorderLevels`.
 *
 * @see docs/data.md
 */
final class ReorderLevelData extends Data
{
    public function __construct(
        public ?string $LocationID = null,
        public ?string $LocationName = null,
        public ?float $MinimumBeforeReorder = null,
        public ?float $ReorderQuantity = null,
        public ?string $StockLocator = null,
        public ?string $PickZones = null,
    ) {
    }
}
