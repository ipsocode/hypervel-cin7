<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionBOMVariationComponent, a variation component of a product family's BOM operation: a
 * product family by `ProductFamilyID` or `ProductFamilySKU`. `BOMVariationComponentID` is required
 * when updating; the family's name, costing method and unit are read-only.
 *
 * @see docs/data.md
 */
final class ProductionBomVariationComponentData extends Data
{
    public function __construct(
        public string $QuantitySettingsJSON,
        public string $MapVariationsJSON,
        #[Max(50)]
        public string $QuantityOptionName,
        public int $Position,
        #[Uuid]
        public ?string $BOMVariationComponentID = null,
        #[RequiredWithout('ProductFamilySKU')]
        #[Uuid]
        public ?string $ProductFamilyID = null,
        #[RequiredWithout('ProductFamilyID')]
        public ?string $ProductFamilySKU = null,
        public ?string $ProductFamilyName = null,
        public ?string $CostingMethod = null,
        public ?string $Unit = null,
        public ?int $SalePriceTier = null,
        public ?float $Cost = null,
    ) {
    }
}
