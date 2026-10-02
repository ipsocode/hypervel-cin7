<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * Sale Fulfilment Ship Line Model.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentShipLineData extends Data
{
    public function __construct(
        #[Uuid]
        public ?string $ID = null,
        #[Date]
        public ?string $ShipmentDate = null,
        #[Max(256)]
        public ?string $Carrier = null,
        #[Max(256)]
        public ?string $Boxes = null,
        #[Max(256)]
        public ?string $TrackingNumber = null,
        #[Max(512)]
        public ?string $TrackingURL = null,
        public ?bool $IsShipped = null,
    ) {
    }
}
