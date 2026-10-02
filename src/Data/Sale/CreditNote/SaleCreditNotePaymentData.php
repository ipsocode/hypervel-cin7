<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\CreditNote;

use Hypervel\Data\Data;

/**
 * One entry of a credit note's `Payments` in the `sale/creditnote` GET with `IncludePaymentInfo`.
 * The reference names no model for it, so it follows the example: a Sale Payment Line plus the
 * order, invoice and credit note numbers, `Type` and `CreditID`.
 *
 * @see docs/data.md
 */
final class SaleCreditNotePaymentData extends Data
{
    public function __construct(
        public ?string $ID = null,
        public ?string $SaleOrderNumber = null,
        public ?string $InvoiceNumber = null,
        public ?string $CreditNoteNumber = null,
        public ?string $Type = null,
        public ?string $Reference = null,
        public ?float $Amount = null,
        public ?string $DatePaid = null,
        public ?string $Account = null,
        public ?float $CurrencyRate = null,
        public ?string $DateCreated = null,
        public ?string $CreditID = null,
    ) {
    }
}
