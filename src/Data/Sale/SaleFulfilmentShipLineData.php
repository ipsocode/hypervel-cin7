<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * Sale Fulfilment Ship Line Model.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentShipLineData extends Data
{
    public function __construct(
        public ?string $ID = null,
        public ?string $ShipmentDate = null,
        public ?string $Carrier = null,
        public ?string $Boxes = null,
        public ?string $TrackingNumber = null,
        public ?string $TrackingURL = null,
        public ?bool $IsShipped = null,
    ) {
    }
}
