<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Payment;

use Hypervel\Data\Data;

/**
 * The body of `sale/payment` POST: the Sale Payment Line Partial Model's fields available for POST.
 * The ones the reference marks required have no default. `Type` is `Prepayment`, `Payment` or
 * `Refund`, the spelling of the examples and notes.
 *
 * @see docs/data.md
 */
final class SalePaymentPostData extends Data
{
    public function __construct(
        public string $TaskID,
        public string $Type,
        public float $Amount,
        public string $DatePaid,
        public string $Account,
        public float $CurrencyRate,
        public ?string $Reference = null,
    ) {
    }
}
