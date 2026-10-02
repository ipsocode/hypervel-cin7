<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Attributes\DateTime;

/**
 * Product Supplier Model, one entry of a product's `Suppliers`. A supplier is named by
 * `SupplierID` or `SupplierName`, so a write body without either fails validation. Nested in a
 * product, the supplier needs no `ProductID` or `ProductSKU`.
 *
 * The table names the supplier's link `SupplierProductURL`; the examples, requests and responses
 * alike, send `URL`. Both keys are modelled.
 *
 * @see docs/data.md
 */
final class ProductSupplierData extends Data
{
    /**
     * @param null|list<ProductSupplierOptionData> $ProductSupplierOptions
     */
    public function __construct(
        #[RequiredWithout('SupplierName')]
        #[Uuid]
        public ?string $SupplierID = null,
        #[RequiredWithout('SupplierID')]
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
        #[Max(256)]
        public ?string $URL = null,
        #[DateTime]
        public ?string $LastSupplied = null,
        #[DataCollectionOf(ProductSupplierOptionData::class)]
        public ?array $ProductSupplierOptions = null,
    ) {
    }
}
