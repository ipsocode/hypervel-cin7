<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `production/order/authorise` POST: the `productionOrderID` to authorise, keyed with
 * a lower-case first letter as the reference writes it.
 *
 * @see docs/data.md
 */
final class ProductionOrderAuthorisePostData extends Data
{
    public function __construct(
        #[Uuid]
        public string $productionOrderID,
    ) {
    }
}
