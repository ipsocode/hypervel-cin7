<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `production/order/void` POST: the `productionOrderID`, keyed with a lower-case first
 * letter as the reference writes it, and whether the void applies to its `ApplyToTasks`.
 *
 * @see docs/data.md
 */
final class ProductionOrderVoidPostData extends Data
{
    public function __construct(
        #[Uuid]
        public string $productionOrderID,
        public ?bool $ApplyToTasks = null,
    ) {
    }
}
