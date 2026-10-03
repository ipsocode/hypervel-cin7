<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `production/productionBOM` PUT for a product: one BOM, which requires its `BOMID`,
 * with the `ProductID` it belongs to. The PUT example sends no `BufferPercent`, `Name` or
 * `IsDefault`, so they are optional here.
 *
 * @see docs/data.md
 */
final class ProductProductionBomPutData extends Data
{
    /**
     * @param null|list<ProductionBomOperationData> $Operations
     */
    public function __construct(
        #[Uuid]
        public string $BOMID,
        #[Uuid]
        public ?string $ProductID = null,
        public ?float $OutputQuantity = null,
        public ?float $BufferPercent = null,
        public ?string $InstructionUrl = null,
        public ?bool $IgnoreCumulativeLeadTime = null,
        public ?int $ComponentProductionLeadTime = null,
        public ?int $Version = null,
        #[Max(256)]
        public ?string $Name = null,
        public ?bool $IsDefault = null,
        public ?bool $IsTracing = null,
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
        #[DataCollectionOf(ProductionBomOperationData::class)]
        public ?array $Operations = null,
    ) {
    }
}
