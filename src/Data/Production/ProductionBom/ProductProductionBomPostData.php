<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `production/productionBOM` POST for a product: its `ProductID` and `ProductionBOMs`,
 * and `SetParentHasProductionBom`, in the example, not the table.
 *
 * @see docs/data.md
 */
final class ProductProductionBomPostData extends Data
{
    /**
     * @param list<ProductionBomData> $ProductionBOMs
     */
    public function __construct(
        #[Uuid]
        public string $ProductID,
        #[DataCollectionOf(ProductionBomData::class)]
        public array $ProductionBOMs,
        public ?bool $SetParentHasProductionBom = null,
    ) {
    }
}
