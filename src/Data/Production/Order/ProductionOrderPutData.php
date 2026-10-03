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
 * The body of `production/order` PUT: the writable fields of the ProductionOrder table, which
 * requires the `ProductionOrderID`. The POST body is `ProductionOrderPostData`.
 *
 * @see docs/data.md
 */
final class ProductionOrderPutData extends Data
{
    /**
     * @param null|list<ProductionOrderOperationData> $ProductionOrderOperations
     */
    public function __construct(
        #[Uuid]
        public string $ProductionOrderID,
        #[Uuid]
        public ?string $ProductID = null,
        #[Uuid]
        public ?string $LocationID = null,
        public ?string $ProductSKU = null,
        public ?string $LocationName = null,
        public string|int|null $SourceName = null,
        public ?string $WIPAccountCode = null,
        public ?string $FinishedGoodsAccountCode = null,
        public ?float $Quantity = null,
        public ?CapacityCalculationType $CapacityCalculationType = null,
        #[DateTime]
        public ?string $StartDate = null,
        #[DateTime]
        public ?string $ReleaseDate = null,
        #[DateTime]
        public ?string $RequiredByDate = null,
        public ?bool $IsIgnoreLeadTime = null,
        #[Max(2000)]
        public ?string $Comments = null,
        public ?int $OrderCycleTime = null,
        public ?int $BOMVersion = null,
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
    ) {
    }
}
