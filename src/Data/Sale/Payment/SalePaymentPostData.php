<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Payment;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Attributes\DateTime;

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
        #[Uuid]
        public string $TaskID,
        public string $Type,
        public float $Amount,
        #[DateTime]
        public string $DatePaid,
        public string $Account,
        public float $CurrencyRate,
        public ?string $Reference = null,
    ) {
    }
}
