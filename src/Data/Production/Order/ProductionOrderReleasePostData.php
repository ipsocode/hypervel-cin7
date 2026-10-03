<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * The body of `production/order/release` POST: the `productionOrderID` to release and its
 * `releaseDate`, keyed with lower-case first letters as the reference writes them.
 *
 * @see docs/data.md
 */
final class ProductionOrderReleasePostData extends Data
{
    public function __construct(
        #[Uuid]
        public string $productionOrderID,
        #[DateTime]
        public ?string $releaseDate = null,
    ) {
    }
}
