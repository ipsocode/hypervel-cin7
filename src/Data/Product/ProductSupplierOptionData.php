<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Product Supplier Options Model, one entry of a product supplier's `ProductSupplierOptions`.
 *
 * @see docs/data.md
 */
final class ProductSupplierOptionData extends Data
{
    /**
     * @param list<ProductSupplierOptionIntervalData>|Optional $SupplyIntervals
     */
    public function __construct(
        public string|Optional $ID,
        public string|Optional $LocationID,
        public string|Optional $LocationName,
        public float|Optional $ReorderQuantity,
        public int|Optional $Lead,
        public int|Optional $Safety,
        public float|Optional $MinimumToReorder,
        #[DataCollectionOf(ProductSupplierOptionIntervalData::class)]
        public array|Optional $SupplyIntervals,
    ) {
    }
}
