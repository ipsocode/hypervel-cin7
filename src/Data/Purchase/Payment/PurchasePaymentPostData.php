<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Payment;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of `purchase/payment` POST: the Purchase Payments fields available for POST, without
 * the PUT-only `ID`. The ones the reference marks required have no default. `Type` is
 * `Prepayment`, `Payment` or `Refund`, the spelling of the examples; `DepositID` (only with `Type`
 * `Payment`) takes the payment from a supplier deposit.
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
