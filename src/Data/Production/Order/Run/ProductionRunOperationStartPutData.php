<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * The body of `production/order/run/operation/start` PUT: the operation to start, whether to
 * consume its components, a `ManualStartDate` and the `InputProducts`, components by `ProductID`
 * or `ProductCode`, with the batch and quantity used.
 *
 * @see docs/data.md
 */
final class ProductionRunOperationStartPutData extends Data
{
    /**
     * @param null|list<ProductionRunOperationComponentData> $InputProducts
     */
    public function __construct(
        #[Uuid]
        public string $ProductionOrderID,
        #[Uuid]
        public string $ProductionRunID,
        #[Uuid]
        public string $RunOperationID,
        public ?bool $WithAutoConsume = null,
        #[DateTime]
        public ?string $ManualStartDate = null,
        #[DataCollectionOf(ProductionRunOperationComponentData::class)]
        public ?array $InputProducts = null,
    ) {
    }
}
