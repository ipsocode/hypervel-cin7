<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Attributes\DateTime;

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
        #[Uuid]
        public ?string $SupplierID = null,
        #[Max(256)]
        public ?string $SupplierName = null,
        #[Uuid]
        public ?string $ProductID = null,
        #[Max(256)]
        public ?string $ProductSKU = null,
        #[Uuid]
        public ?string $ProductSupplierID = null,
        #[Max(256)]
        public ?string $SupplierInventoryCode = null,
        #[Max(256)]
        public ?string $SupplierProductName = null,
        public ?float $Cost = null,
        public ?float $FixedCost = null,
        public ?string $Currency = null,
        public ?bool $DropShip = null,
        #[Max(256)]
        public ?string $SupplierProductURL = null,
        #[DateTime]
        public ?string $LastSupplied = null,
        #[DataCollectionOf(ProductSupplierOptionData::class)]
        public ?array $ProductSupplierOptions = null,
    ) {
    }
}
