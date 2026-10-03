<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * The body of `production/order/run/operation/complete` PUT: the operation to complete, whether to
 * consume its components, to complete it `WithoutOutput` and to `AutoGenerateBatchSerialNumbers`,
 * a `ManualEndDate` and its `FinishedProducts`.
 *
 * @see docs/data.md
 */
final class ProductionRunOperationCompletePutData extends Data
{
    /**
     * @param null|list<ProductionRunOutputData> $FinishedProducts
     */
    public function __construct(
        #[Uuid]
        public string $ProductionOrderID,
        #[Uuid]
        public string $ProductionRunID,
        #[Uuid]
        public string $RunOperationID,
        public ?bool $WithAutoConsume = null,
        public ?bool $WithoutOutput = null,
        public ?bool $AutoGenerateBatchSerialNumbers = null,
        #[DateTime]
        public ?string $ManualEndDate = null,
        #[DataCollectionOf(ProductionRunOutputData::class)]
        public ?array $FinishedProducts = null,
    ) {
    }
}
