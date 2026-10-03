<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Payment;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchasePaymentData;

/**
 * The body of `purchase/payment` PUT: the Available Fields for Purchase Payments table's fields
 * available for PUT, with the `ID` of the payment to change, which PUT requires, and without the
 * POST-only `Type` and `DepositID`. The table requires `Amount` and `Account` and the PUT example
 * sends them, so they stay required, although the reference says a payment taken from a deposit
 * cannot change them: send the payment's own values. A prepayment cannot be changed. The POST body
 * is `PurchasePaymentPostData`.
 *
 * @see docs/data.md
 */
final class PurchasePaymentPutData extends AbstractPurchasePaymentData
{
    public function __construct(
        string $TaskID,
        string $DatePaid,
        float $CurrencyRate,
        float $Amount,
        string $Account,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($TaskID, $DatePaid, $CurrencyRate, $Amount, $Account);
    }
}
