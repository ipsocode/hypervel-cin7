<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment\Ship;

use Ipsocode\Cin7\Enums\ShipmentStatus;

/**
 * The body of `sale/fulfilment/ship` POST, which creates a shipment or adds lines to one. The PUT
 * body is `SaleFulfilmentShipPutData`.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentShipPostData extends AbstractSaleFulfilmentShipTaskData
{
    public function __construct(
        string $TaskID,
        ShipmentStatus $Status,
    ) {
        parent::__construct($TaskID, $Status);
    }
}
