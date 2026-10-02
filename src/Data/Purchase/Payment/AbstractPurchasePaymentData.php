<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Payment;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * The fields every purchase payment model shares: the Available Fields for Purchase Payments
 * table of `purchase/payment`, as its responses and its POST and PUT bodies carry it. All three
 * require `TaskID`, `DatePaid` and `CurrencyRate`, so each child passes them to this
 * constructor; `Amount` and `Account`, which a PUT of a deposit payment does not take, stay in
 * the children's constructors, and the optional fields declared here are set through `from()`.
 * `DateCreated` is the date Cin7 stamps on the payment record, but the reference's request
 * examples send it, so it is modelled, and the requests leave it out of the body.
 *
 * @see docs/data.md
 */
abstract class AbstractPurchasePaymentData extends Data
{
    public ?string $Reference = null;

    #[DateTime]
    public ?string $DateCreated = null;

    public function __construct(
        #[Uuid]
        public string $TaskID,
        #[DateTime]
        public string $DatePaid,
        public float $CurrencyRate,
    ) {
    }
}
