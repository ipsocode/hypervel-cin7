<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\Payment;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\AbstractPurchasePaymentData;

/**
 * Advanced Purchase Payments, one payment of the `advanced-purchase/payment` responses: the
 * Available Fields for Purchase Payments table with its `ID`, `Type` and `DepositID`, plus the
 * `PurchaseID` of the advanced purchase, which only the examples send. The bodies of POST and PUT
 * are `AdvancedPurchasePaymentPostData` and `AdvancedPurchasePaymentPutData`.
 *
 * The fields the reference marks required have no default; `ID` is a bare `Yes*`, so it is
 * optional here, as is the examples' `PurchaseID`. The examples spell `Type` `Payment` and
 * `Refund` where the table has `PAYMENT`, `REFUND` and `PREPAYMENT`, so it is a string.
 *
 * @see docs/data.md
 */
final class AdvancedPurchasePaymentData extends AbstractPurchasePaymentData implements WithResponse
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
        public ?string $PurchaseID = null,
        #[Uuid]
        public ?string $ID = null,
        #[Uuid]
        public ?string $DepositID = null,
    ) {
        parent::__construct($TaskID, $DatePaid, $CurrencyRate);
    }
}
