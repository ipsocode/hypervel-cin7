<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * The fields of the Sale Payment Line Model, which the payments of a credit note extend with the
 * order, invoice and credit note numbers, `Type` and `CreditID`. A field declared here is set
 * through `from()`, not the child's constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractSalePaymentLineData extends Data
{
    #[Uuid]
    public ?string $ID = null;

    public ?string $Reference = null;

    public ?float $Amount = null;

    #[DateTime]
    public ?string $DatePaid = null;

    public ?string $Account = null;

    public ?float $CurrencyRate = null;

    #[DateTime]
    public ?string $DateCreated = null;
}
