<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `production/order` PUT: the writable fields of the ProductionOrder table, which
 * requires the `ProductionOrderID` and `LocationID`. The table requires the `ProductID` too, but
 * the PUT example sends none, so it is optional here. The POST body is `ProductionOrderPostData`.
 *
 * @see docs/data.md
 */
final class ProductionOrderPutData extends AbstractProductionOrderData
{
    public function __construct(
        string $LocationID,
        #[Uuid]
        public string $ProductionOrderID,
        #[Uuid]
        public ?string $ProductID = null,
    ) {
        parent::__construct($LocationID);
    }
}
