<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionRunTraceability, which used component batch went into which produced batch.
 * `TraceabilityID` is required when updating.
 *
 * @see docs/data.md
 */
final class ProductionRunTraceabilityData extends Data
{
    public function __construct(
        #[Uuid]
        public string $ProducedProductID,
        #[Max(50)]
        public string $ProducedProductBatchSN,
        #[Uuid]
        public string $UsedProductID,
        #[Max(50)]
        public string $UsedProductBatchSN,
        #[Uuid]
        public ?string $TraceabilityID = null,
        #[DateTime]
        public ?string $ProducedProductExpiryDate = null,
        #[DateTime]
        public ?string $UsedProductExpiryDate = null,
    ) {
    }
}
