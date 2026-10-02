<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Payment;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Purchase Payments, one payment of the `purchase/payment` responses: the Available Fields for
 * Purchase Payments table with its `ID`, `Type` and `DepositID`. The bodies of POST and PUT are
 * `PurchasePaymentPostData` and `PurchasePaymentPutData`.
 *
 * The fields the reference marks required have no default; `ID` is a bare `Yes*`, so it is
 * optional here. The examples spell `Type` `Payment` and `Refund` where the table has `PAYMENT`,
 * `REFUND` and `PREPAYMENT`, so it is a string.
 *
 * @see docs/data.md
 */
final class PurchasePaymentData extends AbstractPurchasePaymentData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $TaskID,
        string $DatePaid,
        float $CurrencyRate,
        public string $Type,
        public float $Amount,
        public string $Account,
        #[Uuid]
        public ?string $ID = null,
        #[Uuid]
        public ?string $DepositID = null,
    ) {
        parent::__construct($TaskID, $DatePaid, $CurrencyRate);
    }
}
