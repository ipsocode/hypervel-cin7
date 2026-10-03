<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `production/order` POST: the writable fields of the ProductionOrder table, which
 * requires a `ProductID` and a `LocationID`; `StartDate` is required for `FromStartForward` and
 * `RequiredByDate` for `FromRequiredBackward`. `BOMVersion` and `IsIgnoreLeadTime` are in the
 * example, not the table. The PUT body is `ProductionOrderPutData`.
 *
 * @see docs/data.md
 */
final class ProductionOrderPostData extends AbstractProductionOrderData
{
    public function __construct(
        string $LocationID,
        #[Uuid]
        public string $ProductID,
    ) {
        parent::__construct($LocationID);
    }
}
