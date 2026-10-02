<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment\Ship;

use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Sale Fulfilment Ship Line Model as POST and PUT take it: the box is `Box` ("For POST/PUT
 * methods use `Box` as the field name"). The read-only `TrackingURL`, which the examples send, is
 * modelled, and the requests leave it out of the body. A response line is
 * `SaleFulfilmentShipLineData`.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentShipLinePostPutData extends Data
{
    public function __construct(
        #[Date]
        public string $ShipmentDate,
        #[Max(256)]
        public string $Box,
        #[Uuid]
        public ?string $ID = null,
        #[Max(256)]
        public ?string $Carrier = null,
        #[Max(256)]
        public ?string $TrackingNumber = null,
        #[Max(512)]
        public ?string $TrackingURL = null,
        public ?bool $IsShipped = null,
    ) {
    }
}
