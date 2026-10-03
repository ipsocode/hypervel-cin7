<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `production/order/run` POST: the `ProductionOrderID`, whether to
 * `IncreaseOrderQuantity` and the `Runs` to create.
 *
 * @see docs/data.md
 */
final class ProductionRunPostData extends Data
{
    /**
     * @param list<ProductionRunData> $Runs
     */
    public function __construct(
        #[Uuid]
        public string $ProductionOrderID,
        #[DataCollectionOf(ProductionRunData::class)]
        public array $Runs,
        public ?bool $IncreaseOrderQuantity = null,
    ) {
    }
}
