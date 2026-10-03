<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionRunOperation, an operation of a run, with its components, resources, costs and
 * products. `OperationID` is required. `Status`, `Order`, `UnitsPerCycle` and the work center's
 * code and name are read-only, and which fields can change depends on the operation's status.
 * `Status` is a string: the examples send values outside the listed ones. `Available` is in the
 * examples, not the table.
 *
 * @see docs/data.md
 */
final class ProductionRunOperationData extends Data
{
    /**
     * @param null|list<ProductionRunOperationComponentData> $Components
     * @param null|list<ProductionRunOperationResourceData> $Resources
     * @param null|list<ProductionRunOperationResourceCostData> $ResourceCosts
     * @param null|list<ProductionRunOperationProductData> $FinishedProducts
     * @param null|list<ProductionRunOperationProductData> $OutputProducts
     * @param null|list<ProductionRunOperationProductData> $InputProducts
     * @param null|list<ProductionRunOperationCoManTaskData> $CoManTasks
     * @param null|list<ProductionRunOperationCoManLineData> $CoManTaskLines
     * @param null|list<ProductionRunOperationAttachmentData> $Attachments
     * @param null|list<ProductionRunOperationNoteData> $Notes
     */
    public function __construct(
        #[Uuid]
        public string $OperationID,
        public ?string $Status = null,
        public ?int $Order = null,
        #[Max(512)]
        public ?string $Name = null,
        public ?int $PlannedTime = null,
        public ?int $ActualTime = null,
        public ?float $UnitsPerCycle = null,
        #[DateTime]
        public ?string $StartDate = null,
        #[DateTime]
        public ?string $EndDate = null,
        #[DateTime]
        public ?string $DueDate = null,
        public ?string $OperationType = null,
        #[Uuid]
        public ?string $WorkCenterID = null,
        public ?string $WorkCenterCode = null,
        public ?string $WorkCenterName = null,
        public ?string $SuspendReason = null,
        public ?string $CoManStatus = null,
        public ?bool $IsDropShip = null,
        #[DataCollectionOf(ProductionRunOperationComponentData::class)]
        public ?array $Components = null,
        #[DataCollectionOf(ProductionRunOperationResourceData::class)]
        public ?array $Resources = null,
        #[DataCollectionOf(ProductionRunOperationResourceCostData::class)]
        public ?array $ResourceCosts = null,
        #[DataCollectionOf(ProductionRunOperationProductData::class)]
        public ?array $FinishedProducts = null,
        #[DataCollectionOf(ProductionRunOperationProductData::class)]
        public ?array $OutputProducts = null,
        #[DataCollectionOf(ProductionRunOperationProductData::class)]
        public ?array $InputProducts = null,
        #[DataCollectionOf(ProductionRunOperationCoManTaskData::class)]
        public ?array $CoManTasks = null,
        #[DataCollectionOf(ProductionRunOperationCoManLineData::class)]
        public ?array $CoManTaskLines = null,
        #[DataCollectionOf(ProductionRunOperationAttachmentData::class)]
        public ?array $Attachments = null,
        #[DataCollectionOf(ProductionRunOperationNoteData::class)]
        public ?array $Notes = null,
        public ?bool $IsBackflush = null,
        #[Date]
        public ?string $ManualStartDate = null,
        #[Date]
        public ?string $ManualEndDate = null,
        public ?string $CustomField1 = null,
        public ?string $CustomField2 = null,
        public ?string $CustomField3 = null,
        public ?string $CustomField4 = null,
        public ?string $CustomField5 = null,
        public ?string $CustomField6 = null,
        public ?string $CustomField7 = null,
        public ?string $CustomField8 = null,
        public ?string $CustomField9 = null,
        public ?string $CustomField10 = null,
    ) {
    }
}
