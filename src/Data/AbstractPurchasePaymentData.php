<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * The fields of the Available Fields for Purchase Payments table that every verb takes: the
 * response of `purchase/payment` and `advanced-purchase/payment` and the body of their POST and
 * PUT. Both paths document the same table, so the simple and the advanced purchase's payment
 * classes share this parent; each is a final child that adds the fields of its verb.
 *
 * Every payment needs its `TaskID`, `DatePaid` and `CurrencyRate`, so each child passes them to
 * this constructor; `Amount` and `Account`, which a PUT of a deposit payment does not take, stay in
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
