<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Payment;

use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Sale Payment Line Partial Model, one payment of the `sale/payment` responses. The fields the
 * reference marks required have no default. The examples spell `Type` `Payment` and `Refund`,
 * and the notes `Prepayment`, where the table has `PAYMENT`, `REFUND` and `PREPAYMENT`. The bodies
 * are `SalePaymentPostData` and `SalePaymentPutData`.
 *
 * @see docs/data.md
 */
final class SalePaymentLinePartialData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        public string $ID,
        public string $TaskID,
        public string $Type,
        public float $Amount,
        public string $DatePaid,
        public string $Account,
        public float $CurrencyRate,
        public ?string $SaleID = null,
        public ?string $SaleOrderNumber = null,
        public ?string $InvoiceNumber = null,
        public ?string $CreditNoteNumber = null,
        public ?string $Reference = null,
        public ?string $DateCreated = null,
        public ?string $CreditID = null,
    ) {
    }
}
