<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `production/productionBOM` PUT for a product family: one BOM, which requires its
 * `BOMID`, with the `ProductFamilyID` it belongs to.
 *
 * @see docs/data.md
 */
final class ProductFamilyProductionBomPutData extends Data
{
    /**
     * @param null|list<ProductFamilyProductionBomOperationData> $Operations
     */
    public function __construct(
        #[Uuid]
        public string $BOMID,
        #[Uuid]
        public ?string $ProductFamilyID = null,
        public ?float $OutputQuantity = null,
        public ?float $BufferPercent = null,
        public ?string $InstructionUrl = null,
        public ?bool $IgnoreCumulativeLeadTime = null,
        public ?int $ComponentProductionLeadTime = null,
        public ?int $Version = null,
        #[Max(256)]
        public ?string $Name = null,
        public ?bool $IsDefault = null,
        #[Uuid]
        public ?string $DeliveryToID = null,
        #[Max(256)]
        public ?string $DeliveryToName = null,
        public string|int|null $IssueMethodComponent = null,
        public string|int|null $IssueMethodParameter = null,
        public ?float $MinQuantity = null,
        public ?float $MaxQuantity = null,
        public ?float $DeviationPercent = null,
        public ?float $RunSize = null,
        #[DataCollectionOf(ProductFamilyProductionBomOperationData::class)]
        public ?array $Operations = null,
    ) {
    }
}
