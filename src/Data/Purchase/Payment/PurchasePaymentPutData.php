<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Payment;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of `purchase/payment` PUT: the Purchase Payments fields available for PUT, with the
 * `ID` of the payment to change and without the POST-only `Type` and `DepositID`. `Amount` and
 * `Account` are not available when the payment is a deposit, so they are optional; the other
 * fields the reference marks required have no default. A prepayment cannot be changed.
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
