<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * The body of `production/order/run/complete` PUT: the run to complete and its `FinishedProducts`,
 * as received.
 *
 * @see docs/data.md
 */
final class ProductionRunCompletePutData extends Data
{
    /**
     * @param null|list<ProductionRunOutputData> $FinishedProducts
     */
    public function __construct(
        #[Uuid]
        public string $ProductionOrderID,
        #[Uuid]
        public string $ProductionRunID,
        #[DateTime]
        public ?string $EffectiveDate = null,
        #[DataCollectionOf(ProductionRunOutputData::class)]
        public ?array $FinishedProducts = null,
    ) {
    }
}
