<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Payment;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchasePaymentData;

/**
 * The body of `purchase/payment` PUT: the Available Fields for Purchase Payments table's fields
 * available for PUT, with the `ID` of the payment to change, which PUT requires, and without the
 * POST-only `Type` and `DepositID`. `Amount` and `Account` are not available when the payment is
 * taken from a deposit, so they are optional; `TaskID`, `DatePaid` and `CurrencyRate` stay
 * required. A prepayment cannot be changed. The POST body is `PurchasePaymentPostData`.
 *
 * @see docs/data.md
 */
final class PurchasePaymentPutData extends AbstractPurchasePaymentData
{
    public function __construct(
        string $TaskID,
        string $DatePaid,
        float $CurrencyRate,
        #[Uuid]
        public string $ID,
        public ?float $Amount = null,
        public ?string $Account = null,
    ) {
        parent::__construct($TaskID, $DatePaid, $CurrencyRate);
    }
}
