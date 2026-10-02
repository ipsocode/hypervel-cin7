<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\CreditNote;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\Sale\AbstractSalePaymentLineData;

/**
 * One entry of a credit note's `Payments` in the `sale/creditnote` GET with `IncludePaymentInfo`.
 * The reference names no model for it, so it follows the example: a Sale Payment Line plus the
 * order, invoice and credit note numbers, `Type` and `CreditID`.
 *
 * @see docs/data.md
 */
final class SaleCreditNotePaymentData extends AbstractSalePaymentLineData
{
    public function __construct(
        public ?string $SaleOrderNumber = null,
        public ?string $InvoiceNumber = null,
        public ?string $CreditNoteNumber = null,
        public ?string $Type = null,
        #[Uuid]
        public ?string $CreditID = null,
    ) {
    }
}
