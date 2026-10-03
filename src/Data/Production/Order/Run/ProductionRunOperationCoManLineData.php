<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * ProductionRunOperationCoManLine, a read-only output line of a co-manufacturing operation.
 *
 * @see docs/data.md
 */
final class ProductionRunOperationCoManLineData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        #[Uuid]
        public ?string $ProductID = null,
        #[Max(50)]
        public ?string $ProductCode = null,
        public ?string $ProductName = null,
        public ?string $Unit = null,
        public ?float $Quantity = null,
        public ?float $Cost = null,
        #[Max(50)]
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        #[Uuid]
        public ?string $LocationID = null,
        public ?string $LocationName = null,
        #[DateTime]
        public ?string $ReceivedDate = null,
    ) {
    }
}
