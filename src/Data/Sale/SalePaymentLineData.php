<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * Sale Payment Line Model, one prepayment, payment or refund.
 *
 * @see docs/data.md
 */
final class SalePaymentLineData extends Data
{
    public function __construct(
        public ?string $ID = null,
        public ?string $Reference = null,
        public ?float $Amount = null,
        public ?string $DatePaid = null,
        public ?string $Account = null,
        public ?float $CurrencyRate = null,
        public ?string $DateCreated = null,
    ) {
    }
}
