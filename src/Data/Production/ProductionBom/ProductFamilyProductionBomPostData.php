<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `production/productionBOM` POST for a product family: its `ProductFamilyID` and
 * `ProductionBOMs`.
 *
 * @see docs/data.md
 */
final class ProductFamilyProductionBomPostData extends Data
{
    /**
     * @param list<ProductFamilyProductionBomData> $ProductionBOMs
     */
    public function __construct(
        #[Uuid]
        public string $ProductFamilyID,
        #[DataCollectionOf(ProductFamilyProductionBomData::class)]
        public array $ProductionBOMs,
    ) {
    }
}
