<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment\Ship;

use Ipsocode\Cin7\Enums\ShipmentStatus;

/**
 * The body of `sale/fulfilment/ship` PUT, which replaces an unauthorised shipment and lists every
 * packed box. `AddTrackingNumbers` (`true`) lets it change the tracking numbers or carrier of an
 * authorised shipment, which the reference documents in prose only. The POST body is
 * `SaleFulfilmentShipPostData`.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentShipPutData extends AbstractSaleFulfilmentShipTaskData
{
    public function __construct(
        string $TaskID,
        ShipmentStatus $Status,
        public ?bool $AddTrackingNumbers = null,
    ) {
        parent::__construct($TaskID, $Status);
    }
}
