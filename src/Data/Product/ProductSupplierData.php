<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;

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
     * @param list<ProductSupplierOptionData>|Optional $ProductSupplierOptions
     */
    public function __construct(
        public string|Optional $SupplierID,
        public string|Optional $SupplierName,
        public string|Optional $ProductID,
        public string|Optional $ProductSKU,
        public string|Optional $ProductSupplierID,
        public string|Optional|null $SupplierInventoryCode,
        public string|Optional|null $SupplierProductName,
        public float|Optional $Cost,
        public float|Optional $FixedCost,
        public string|Optional $Currency,
        public bool|Optional $DropShip,
        public string|Optional|null $SupplierProductURL,
        public string|Optional|null $LastSupplied,
        #[DataCollectionOf(ProductSupplierOptionData::class)]
        public array|Optional $ProductSupplierOptions,
    ) {
    }
}
