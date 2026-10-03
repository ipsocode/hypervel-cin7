<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\OrderList;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\CapacityCalculationType;
use Ipsocode\Cin7\Enums\ProductionOrderListType;

/**
 * ProductionOrderListItem, one entry of `ProductionOrderListItems`: a production order or one of
 * its runs, by `Type`. `TotalCount` is in the example, not the table.
 *
 * @see docs/data.md
 */
final class ProductionOrderListData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<ProductionOrderListSourceTaskData> $SourceTasks
     */
    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        #[Uuid]
        public ?string $ProductionOrderID = null,
        public ?ProductionOrderListType $Type = null,
        #[Uuid]
        public ?string $ProductID = null,
        public ?string $ProductSKU = null,
        public ?string $ProductName = null,
        public ?string $OrderNumber = null,
        #[Uuid]
        public ?string $LocationID = null,
        public ?string $LocationName = null,
        public ?string $Status = null,
        public ?string $OrderStatus = null,
        public ?float $Quantity = null,
        #[DateTime]
        public ?string $StartDate = null,
        #[DateTime]
        public ?string $ReleaseDate = null,
        #[DateTime]
        public ?string $RequiredByDate = null,
        #[DateTime]
        public ?string $CompletionDate = null,
        #[Max(2000)]
        public ?string $Comments = null,
        public ?CapacityCalculationType $CapacityCalculationType = null,
        public ?string $WIPAccountCode = null,
        public ?string $Tags = null,
        public ?string $FinishedGoodsAccountCode = null,
        #[Uuid]
        public ?string $SourceTaskID = null,
        public ?string $SourceTaskNumber = null,
        public ?int $SourceTaskType = null,
        public ?bool $IsSourceTaskVoided = null,
        public ?float $TotalCount = null,
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
        #[DataCollectionOf(ProductionOrderListSourceTaskData::class)]
        public ?array $SourceTasks = null,
    ) {
    }
}
