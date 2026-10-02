<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Payment;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Attributes\DateTime;

/**
 * The body of `sale/payment` PUT: the Sale Payment Line Partial Model's fields available for PUT.
 * Only `ID` is required: `Amount` and `Account` are not available when the payment is taken from
 * a credit, and `CreditID` (only with `Type` `Payment`) takes it from one.
 *
 * @see docs/data.md
 */
final class SalePaymentPutData extends Data
{
    public function __construct(
        #[Uuid]
        public string $ID,
        public ?string $Reference = null,
        public ?float $Amount = null,
        #[DateTime]
        public ?string $DatePaid = null,
        public ?string $Account = null,
        public ?float $CurrencyRate = null,
        #[Uuid]
        public ?string $CreditID = null,
    ) {
    }
}
