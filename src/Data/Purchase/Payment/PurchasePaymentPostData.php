<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Payment;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchasePaymentData;

/**
 * The body of `purchase/payment` POST: the Available Fields for Purchase Payments table's fields
 * available for POST, without the PUT-only `ID`. The ones the reference marks required have no
 * default. `Type` is `Prepayment`, `Payment` or `Refund`, the spelling of the examples and notes;
 * `DepositID`, which goes only with `Type` `Payment`, takes the payment from a supplier deposit.
 * The PUT body is `PurchasePaymentPutData`.
 *
 * @see docs/data.md
 */
final class PurchasePaymentPostData extends AbstractPurchasePaymentData
{
    public function __construct(
        string $TaskID,
        string $DatePaid,
        float $CurrencyRate,
        public string $Type,
        public float $Amount,
        public string $Account,
        #[Uuid]
        public ?string $DepositID = null,
    ) {
        parent::__construct($TaskID, $DatePaid, $CurrencyRate);
    }
}
