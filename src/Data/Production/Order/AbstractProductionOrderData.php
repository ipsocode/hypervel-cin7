<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Concerns\HasCustomFields;
use Ipsocode\Cin7\Enums\CapacityCalculationType;

/**
 * The fields of the ProductionOrder table that `production/order`'s response and its POST and PUT
 * bodies share, optional in each. The three require a `LocationID`, which the constructor takes; the
 * response adds the rest of the table and the bodies the `ProductID` and `ProductionOrderID` they
 * each require (the PUT example sends no `ProductID`, so it is optional there). Each is a final
 * child.
 *
 * @see docs/data.md
 */
abstract class AbstractProductionOrderData extends Data
{
    use HasCustomFields;

    public ?string $ProductSKU = null;

    public ?string $LocationName = null;

    public string|int|null $SourceName = null;

    public ?string $WIPAccountCode = null;

    public ?string $FinishedGoodsAccountCode = null;

    public ?float $Quantity = null;

    public ?CapacityCalculationType $CapacityCalculationType = null;

    #[DateTime]
    public ?string $StartDate = null;

    #[DateTime]
    public ?string $ReleaseDate = null;

    #[DateTime]
    public ?string $RequiredByDate = null;

    public ?bool $IsIgnoreLeadTime = null;

    #[Max(2000)]
    public ?string $Comments = null;

    public ?int $OrderCycleTime = null;

    public ?int $BOMVersion = null;

    public ?string $Tags = null;

    /**
     * @var null|list<ProductionOrderOperationData>
     */
    #[DataCollectionOf(ProductionOrderOperationData::class)]
    public ?array $ProductionOrderOperations = null;

    public function __construct(
        #[Uuid]
        public string $LocationID,
    ) {
    }
}
