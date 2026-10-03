<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * The answer of `production/order/undo` and `production/order/void`: a `Message` and the
 * `ProductionOrderID`.
 *
 * @see docs/data.md
 */
final class ProductionOrderMessageData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        public ?string $Message = null,
        #[Uuid]
        public ?string $ProductionOrderID = null,
    ) {
    }
}
