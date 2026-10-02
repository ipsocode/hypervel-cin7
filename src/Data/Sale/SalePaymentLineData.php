<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Sale Payment Line Model, one prepayment, payment or refund.
 *
 * @see docs/data.md
 */
final class SalePaymentLineData extends Data
{
    public function __construct(
        public string|Optional $ID,
        public string|Optional $Reference,
        public float|Optional $Amount,
        public string|Optional $DatePaid,
        public string|Optional $Account,
        public float|Optional $CurrencyRate,
        public string|Optional $DateCreated,
    ) {
    }
}
