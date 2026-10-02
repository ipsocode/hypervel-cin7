<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Data\AbstractLineData;

/**
 * Sale Invoice Line Model, also used by credit notes: the line fields of `AbstractLineData`, the
 * sale's `AverageCost` and the revenue `Account`.
 *
 * @see docs/data.md
 */
final class SaleInvoiceLineData extends AbstractLineData
{
    public ?float $AverageCost = null;

    #[Max(50)]
    public ?string $Account = null;
}
