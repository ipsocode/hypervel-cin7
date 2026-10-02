<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Reorder Level Model, one entry of a product's `ReorderLevels`.
 *
 * @see docs/data.md
 */
final class ReorderLevelData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $LocationID = null,
        #[Max(256)]
        public ?string $LocationName = null,
        public ?float $MinimumBeforeReorder = null,
        public ?float $ReorderQuantity = null,
        #[Max(256)]
        public ?string $StockLocator = null,
        #[Max(512)]
        public ?string $PickZones = null,
    ) {
    }
}
