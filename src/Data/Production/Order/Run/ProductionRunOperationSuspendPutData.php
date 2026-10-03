<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `production/order/run/operation/suspend` PUT: the operation to suspend and the
 * `SuspendReasonID`.
 *
 * @see docs/data.md
 */
final class ProductionRunOperationSuspendPutData extends Data
{
    public function __construct(
        #[Uuid]
        public string $ProductionOrderID,
        #[Uuid]
        public string $ProductionRunID,
        #[Uuid]
        public string $RunOperationID,
        #[Uuid]
        public string $SuspendReasonID,
    ) {
    }
}
