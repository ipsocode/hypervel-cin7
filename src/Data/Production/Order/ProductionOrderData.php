<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

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
final class ProductionOrderData extends AbstractProductionOrderData
{
    /**
     * @param null|list<ProductionOrderOperationData> $Operations
     * @param null|list<mixed> $ProductionRuns
     * @param null|list<mixed> $ProductionOrderDeliveryTo
     * @param null|list<mixed> $Deliveries
     * @param null|list<ProductionOrderSourceTaskData> $SourceTasks
     */
    public function __construct(
        string $LocationID,
        #[Uuid]
        public string $ProductionOrderID,
        #[Uuid]
        public string $ProductID,
        public ?string $ProductName = null,
        public ?string $OrderNumber = null,
        public ?string $CostingMethod = null,
        public ?string $WarehouseName = null,
        public ?string $Unit = null,
        public ?string $OrderStatus = null,
        public ?string $Status = null,
        public ?string $InstructionURL = null,
        #[Uuid]
        public ?string $SourceTaskID = null,
        public ?string $SourceTaskNumber = null,
        public ?float $BOMQuantity = null,
        #[DateTime]
        public ?string $CompletionDate = null,
        #[Uuid]
        public ?string $WarehouseID = null,
        #[Uuid]
        public ?string $RetailID = null,
        #[DateTime]
        public ?string $ScheduleStart = null,
        public ?bool $StartUpdate = null,
        #[Max(2000)]
        public ?string $PlannedBy = null,
        #[Max(2000)]
        public ?string $ReleasedBy = null,
        public ?string $BOMName = null,
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
        parent::__construct($LocationID);
    }
}
