<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Product Supplier Options Model, one entry of a product supplier's `ProductSupplierOptions`. Its
 * location is named by `LocationID` or `LocationName`, so a write body without either fails
 * validation.
 *
 * @see docs/data.md
 */
final class ProductSupplierOptionData extends Data
{
    /**
     * @param null|list<ProductSupplierOptionIntervalData> $SupplyIntervals
     */
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        #[RequiredWithout('LocationName')]
        #[Uuid]
        public ?string $LocationID = null,
        #[RequiredWithout('LocationID')]
        #[Max(256)]
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
