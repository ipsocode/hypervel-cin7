<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Payment;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractSalePaymentLineData;

/**
 * Purchase Payment Line Model, one payment or refund of an advanced purchase's invoice or credit
 * note: the Sale Payment Line Model's fields with the purchase's `PurchaseID` and the task's
 * `TaskID`, so it extends `AbstractSalePaymentLineData`. The table requires no field. It is in the
 * folder of the path it is named for, though the advanced purchase's invoices and credit notes are
 * what embed it.
 *
 * @see docs/data.md
 */
final class PurchasePaymentLineData extends AbstractSalePaymentLineData
{
    public function __construct(
        #[Uuid]
        public ?string $PurchaseID = null,
        #[Uuid]
        public ?string $TaskID = null,
    ) {
    }
}
