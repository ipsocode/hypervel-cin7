<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Payment;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Sale Payment Line Partial Model, one payment of `sale/payment`. `TaskID` and `Type` are what POST sends alongside `SaleID`; `ID` is what PUT sends; `CreditID` (PUT, only with `Type` `PAYMENT`) takes the payment from a credit; the rest is in both.
 *
 * @see docs/data.md
 */
final class SalePaymentLinePartialData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        public string|Optional $ID,
        public string|Optional $SaleID,
        public string|Optional $TaskID,
        public string|Optional|null $SaleOrderNumber,
        public string|Optional|null $InvoiceNumber,
        public string|Optional|null $CreditNoteNumber,
        public string|Optional $Type,
        public string|Optional $Reference,
        public float|Optional $Amount,
        public string|Optional $DatePaid,
        public string|Optional $Account,
        public float|Optional $CurrencyRate,
        public string|Optional $DateCreated,
        public string|Optional|null $CreditID,
    ) {
    }
}
