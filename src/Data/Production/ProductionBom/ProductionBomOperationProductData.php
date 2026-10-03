<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionBOMOperationProduct, an input, output or finished product of a BOM operation: a
 * product by `ProductID` or `ProductSKU`, or in a product family's BOM by `ProductFamilyID`. `ID`
 * is required when updating; the product's name, costing method, unit and average cost are
 * read-only.
 *
 * @see docs/data.md
 */
final class ProductionBomOperationProductData extends Data
{
    public function __construct(
        public string $CostCalculationType,
        public float $OutputQuantity,
        public int $Position,
        #[Uuid]
        public ?string $ID = null,
        #[Uuid]
        public ?string $ProductID = null,
        #[Uuid]
        public ?string $ProductFamilyID = null,
        public ?string $ProductSKU = null,
        public ?string $ProductName = null,
        public ?int $PriceTier = null,
        public ?float $Ratio = null,
        public ?string $CostingMethod = null,
        public ?string $Unit = null,
        public ?float $AverageCost = null,
        public ?float $WastageCost = null,
        #[Uuid]
        public ?string $DeliveryTo = null,
        #[Max(256)]
        public ?string $DeliveryToName = null,
    ) {
    }
}
