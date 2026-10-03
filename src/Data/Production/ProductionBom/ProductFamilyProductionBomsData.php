<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * The production BOMs of a product family, the response of every `production/productionBOM` action
 * on a family: its `ProductFamilyID` and `ProductionBOMs`.
 *
 * @see docs/data.md
 */
final class ProductFamilyProductionBomsData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<ProductFamilyProductionBomData> $ProductionBOMs
     */
    public function __construct(
        #[Uuid]
        public ?string $ProductFamilyID = null,
        #[DataCollectionOf(ProductFamilyProductionBomData::class)]
        public ?array $ProductionBOMs = null,
    ) {
    }
}
