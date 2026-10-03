<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * The production BOMs of a product, the response of every `production/productionBOM` action on a
 * product: its `ProductID` and `ProductionBOMs`.
 *
 * @see docs/data.md
 */
final class ProductProductionBomsData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<ProductionBomData> $ProductionBOMs
     */
    public function __construct(
        #[Uuid]
        public ?string $ProductID = null,
        #[DataCollectionOf(ProductionBomData::class)]
        public ?array $ProductionBOMs = null,
    ) {
    }
}
