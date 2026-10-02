<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Payment;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Attributes\DateTime;

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
        #[Uuid]
        public string $ID,
        #[Uuid]
        public string $TaskID,
        public string $Type,
        public float $Amount,
        #[DateTime]
        public string $DatePaid,
        public string $Account,
        public float $CurrencyRate,
        #[Uuid]
        public ?string $SaleID = null,
        public ?string $SaleOrderNumber = null,
        public ?string $InvoiceNumber = null,
        public ?string $CreditNoteNumber = null,
        public ?string $Reference = null,
        #[DateTime]
        public ?string $DateCreated = null,
        #[Uuid]
        public ?string $CreditID = null,
    ) {
    }
}
