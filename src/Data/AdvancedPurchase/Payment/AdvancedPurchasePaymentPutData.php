<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\Payment;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchasePaymentData;

/**
 * The body of `advanced-purchase/payment` PUT: the Available Fields for Purchase Payments table's
 * fields available for PUT, with the `ID` of the payment to change, which PUT requires, and without
 * the POST-only `Type` and `DepositID`. The table requires `Amount` and `Account` and the PUT
 * example sends them, so they stay required, although the reference says a payment taken from a
 * deposit cannot change them: send the payment's own values. A prepayment cannot be changed. The
 * POST body is `AdvancedPurchasePaymentPostData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchasePaymentPutData extends AbstractPurchasePaymentData
{
    public function __construct(
        string $TaskID,
        string $DatePaid,
        float $CurrencyRate,
        #[Uuid]
        public string $ID,
        public float $Amount,
        public string $Account,
    ) {
        parent::__construct($TaskID, $DatePaid, $CurrencyRate);
    }
}
