<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionBOM, a production BOM of a product, with its operations. `BOMID` is required when
 * updating; `IssueMethod` is read-only. `IssueMethod`, `IssueMethodComponent` and
 * `IssueMethodParameter` are codes (`Manual = 1`, `Backflush = 2`, `ExternalApi = 4`, …), numbers
 * in the examples. `ProductID`, `CreatedDate`, `CreatedBy` and `DeliveryTo` are in the examples,
 * not the table.
 *
 * @see docs/data.md
 */
final class ProductionBomData extends Data
{
    /**
     * @param null|list<ProductionBomOperationData> $Operations
     */
    public function __construct(
        public float $OutputQuantity,
        public float $BufferPercent,
        public int $Version,
        #[Max(256)]
        public string $Name,
        public bool $IsDefault,
        #[Uuid]
        public ?string $BOMID = null,
        #[Uuid]
        public ?string $ProductID = null,
        public ?string $InstructionUrl = null,
        public ?bool $IgnoreCumulativeLeadTime = null,
        public ?int $ComponentProductionLeadTime = null,
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
        #[DataCollectionOf(ProductionBomOperationData::class)]
        public ?array $Operations = null,
    ) {
    }
}
