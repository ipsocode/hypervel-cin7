<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Concerns\HasProductionProductFields;

/**
 * ProductionRunOperationCoManLine, a read-only output line of a co-manufacturing operation.
 *
 * @see docs/data.md
 */
final class ProductionRunOperationCoManLineData extends Data
{
    use HasProductionProductFields;

    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        public ?float $Quantity = null,
        public ?float $Cost = null,
        #[Uuid]
        public ?string $LocationID = null,
        public ?string $LocationName = null,
        #[DateTime]
        public ?string $ReceivedDate = null,
    ) {
    }
}
