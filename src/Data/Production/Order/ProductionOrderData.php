<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\CapacityCalculationType;

/**
 * ProductionOrder, a production order, the response of `production/order`, with its operations and
 * source tasks. It requires the `ProductionOrderID`, `ProductID` and `LocationID` its table does,
 * which every response sends. `OrderStatus` and `Status` are strings: the examples send `AUTHORISED`, `ACTIVE`
 * and others outside the listed values. The documented keys `ProductionOrderOperations`,
 * `ProductionRuns` and `ProductionOrderDeliveryTo` are `Operations`, and `Deliveries` in the
 * examples; both are modelled, and the runs and deliveries are lists of whatever the reference
 * puts in them. `StartUpdate`, `Operations` and `Deliveries` are in the examples, not the table.
 *
 * @see docs/data.md
 */
final class ProductionOrderData extends Data
{
    /**
     * @param null|list<ProductionOrderOperationData> $ProductionOrderOperations
     * @param null|list<ProductionOrderOperationData> $Operations
     * @param null|list<mixed> $ProductionRuns
     * @param null|list<mixed> $ProductionOrderDeliveryTo
     * @param null|list<mixed> $Deliveries
     * @param null|list<ProductionOrderSourceTaskData> $SourceTasks
     */
    public function __construct(
        #[Uuid]
        public string $ProductionOrderID,
        #[Uuid]
        public string $ProductID,
        #[Uuid]
        public string $LocationID,
        public ?string $ProductSKU = null,
        public ?string $ProductName = null,
        public ?string $OrderNumber = null,
        public ?string $LocationName = null,
        public ?string $CostingMethod = null,
        public ?string $WarehouseName = null,
        public ?string $Unit = null,
        public ?string $OrderStatus = null,
        public ?string $Status = null,
        public ?string $InstructionURL = null,
        public string|int|null $SourceName = null,
        #[Uuid]
        public ?string $SourceTaskID = null,
        public ?string $SourceTaskNumber = null,
        public ?string $WIPAccountCode = null,
        public ?string $FinishedGoodsAccountCode = null,
        public ?float $Quantity = null,
        public ?float $BOMQuantity = null,
        public ?CapacityCalculationType $CapacityCalculationType = null,
        #[DateTime]
        public ?string $StartDate = null,
        #[DateTime]
        public ?string $ReleaseDate = null,
        #[DateTime]
        public ?string $RequiredByDate = null,
        #[DateTime]
        public ?string $CompletionDate = null,
        public ?bool $IsIgnoreLeadTime = null,
        #[Uuid]
        public ?string $WarehouseID = null,
        #[Uuid]
        public ?string $RetailID = null,
        #[Max(2000)]
        public ?string $Comments = null,
        #[DateTime]
        public ?string $ScheduleStart = null,
        public ?bool $StartUpdate = null,
        #[Max(2000)]
        public ?string $PlannedBy = null,
        #[Max(2000)]
        public ?string $ReleasedBy = null,
        public ?int $OrderCycleTime = null,
        public ?int $BOMVersion = null,
        public ?string $BOMName = null,
        public ?string $Tags = null,
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
        #[DataCollectionOf(ProductionOrderOperationData::class)]
        public ?array $ProductionOrderOperations = null,
        #[DataCollectionOf(ProductionOrderOperationData::class)]
        public ?array $Operations = null,
        public ?array $ProductionRuns = null,
        public ?array $ProductionOrderDeliveryTo = null,
        public ?array $Deliveries = null,
        public string|int|null $IssueMethodComponent = null,
        public string|int|null $IssueMethodParameter = null,
        #[DataCollectionOf(ProductionOrderSourceTaskData::class)]
        public ?array $SourceTasks = null,
        public ?float $RunSize = null,
    ) {
    }
}
