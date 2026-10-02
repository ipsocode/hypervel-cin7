<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Reorder Level Model, one entry of a product's `ReorderLevels`.
 *
 * @see docs/data.md
 */
final class ReorderLevelData extends Data
{
    public function __construct(
        public string|Optional $LocationID,
        public string|Optional $LocationName,
        public float|Optional $MinimumBeforeReorder,
        public float|Optional $ReorderQuantity,
        public string|Optional|null $StockLocator,
        public string|Optional $PickZones,
    ) {
    }
}
