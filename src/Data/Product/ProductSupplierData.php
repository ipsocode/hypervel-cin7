<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;

/**
 * Product Supplier Model, one entry of a product's `Suppliers`.
 *
 * The reference's POST example sends the link as `URL`; its table names it `SupplierProductURL`, which is the wire key here.
 *
 * @see docs/data.md
 */
final class ProductSupplierData extends Data
{
    /**
     * @param null|list<ProductSupplierOptionData> $ProductSupplierOptions
     */
    public function __construct(
        public ?string $SupplierID = null,
        public ?string $SupplierName = null,
        public ?string $ProductID = null,
        public ?string $ProductSKU = null,
        public ?string $ProductSupplierID = null,
        public ?string $SupplierInventoryCode = null,
        public ?string $SupplierProductName = null,
        public ?float $Cost = null,
        public ?float $FixedCost = null,
        public ?string $Currency = null,
        public ?bool $DropShip = null,
        public ?string $SupplierProductURL = null,
        public ?string $LastSupplied = null,
        #[DataCollectionOf(ProductSupplierOptionData::class)]
        public ?array $ProductSupplierOptions = null,
    ) {
    }
}
