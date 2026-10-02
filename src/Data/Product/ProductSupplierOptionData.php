<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;

/**
 * Product Supplier Options Model, one entry of a product supplier's `ProductSupplierOptions`.
 *
 * @see docs/data.md
 */
final class ProductSupplierOptionData extends Data
{
    /**
     * @param null|list<ProductSupplierOptionIntervalData> $SupplyIntervals
     */
    public function __construct(
        public ?string $ID = null,
        public ?string $LocationID = null,
        public ?string $LocationName = null,
        public ?float $ReorderQuantity = null,
        public ?int $Lead = null,
        public ?int $Safety = null,
        public ?float $MinimumToReorder = null,
        #[DataCollectionOf(ProductSupplierOptionIntervalData::class)]
        public ?array $SupplyIntervals = null,
    ) {
    }
}
