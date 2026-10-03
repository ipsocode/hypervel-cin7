<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order\Run;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `production/order/run/operation/resume` PUT: the operation to resume.
 *
 * @see docs/data.md
 */
final class ProductionRunOperationResumePutData extends Data
{
    public function __construct(
        #[Uuid]
        public string $ProductionOrderID,
        #[Uuid]
        public string $ProductionRunID,
        #[Uuid]
        public string $RunOperationID,
    ) {
    }
}
