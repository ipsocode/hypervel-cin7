<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Reorder Level Model, one entry of a product's `ReorderLevels`. It needs its `PickZones`, and a
 * location named by `LocationID` or `LocationName`.
 *
 * @see docs/data.md
 */
final class ReorderLevelData extends Data
{
    public function __construct(
        #[Max(512)]
        public string $PickZones,
        #[RequiredWithout('LocationName')]
        #[Uuid]
        public ?string $LocationID = null,
        #[RequiredWithout('LocationID')]
        #[Max(256)]
        public ?string $LocationName = null,
        public ?float $MinimumBeforeReorder = null,
        public ?float $ReorderQuantity = null,
        #[Max(256)]
        public ?string $StockLocator = null,
    ) {
    }
}
