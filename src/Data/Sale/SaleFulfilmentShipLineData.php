<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Fulfilment Ship Line Model.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentShipLineData extends Data
{
    public function __construct(
        public string|Optional $ID,
        public string|Optional $ShipmentDate,
        public string|Optional $Carrier,
        public string|Optional $Boxes,
        public string|Optional $TrackingNumber,
        public string|Optional $TrackingURL,
        public bool|Optional $IsShipped,
    ) {
    }
}
