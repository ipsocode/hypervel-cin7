<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionBOM of a product family, with its operations, which add `VariationComponents`. The
 * table requires `Version`, `Name` and `IsDefault`, and every response sends them, but the product
 * family POST example sends none, so they are nullable and `#[Required]`, which only a write body
 * checks. `BOMID` is required when updating; `IssueMethod` is read-only. `IssueMethod`,
 * `IssueMethodComponent` and `IssueMethodParameter` are codes (`Manual = 1`, `Backflush = 2`,
 * `ExternalApi = 4`, …), numbers in the examples. `ProductID`, `CreatedDate`, `CreatedBy` and
 * `DeliveryTo` are in the examples, not the table.
 *
 * @see docs/data.md
 */
final class ProductFamilyProductionBomData extends Data
{
    /**
     * @param null|list<ProductFamilyProductionBomOperationData> $Operations
     */
    public function __construct(
        public float $OutputQuantity,
        public float $BufferPercent,
        #[Uuid]
        public ?string $BOMID = null,
        #[Uuid]
        public ?string $ProductID = null,
        public ?string $InstructionUrl = null,
        public ?bool $IgnoreCumulativeLeadTime = null,
        public ?int $ComponentProductionLeadTime = null,
        #[Required]
        public ?int $Version = null,
        #[Required]
        #[Max(256)]
        public ?string $Name = null,
        #[Required]
        public ?bool $IsDefault = null,
        public ?bool $IsTracing = null,
        #[Uuid]
        public ?string $DeliveryToID = null,
        #[Max(256)]
        public ?string $DeliveryToName = null,
        public string|int|null $IssueMethod = null,
        public string|int|null $IssueMethodComponent = null,
        public string|int|null $IssueMethodParameter = null,
        public ?float $MinQuantity = null,
        public ?float $MaxQuantity = null,
        public ?float $DeviationPercent = null,
        public ?float $RunSize = null,
        #[DateTime]
        public ?string $CreatedDate = null,
        public ?string $CreatedBy = null,
        public ?string $DeliveryTo = null,
        #[DataCollectionOf(ProductFamilyProductionBomOperationData::class)]
        public ?array $Operations = null,
    ) {
    }
}
