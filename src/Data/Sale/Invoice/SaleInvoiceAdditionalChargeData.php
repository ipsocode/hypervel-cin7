<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Invoice;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Data\AbstractChargeData;

/**
 * Sale Invoice Additional Charge Model, also used by credit notes: the charge fields of
 * `AbstractChargeData`, a `Comment` and the revenue `Account`.
 *
 * @see docs/data.md
 */
final class SaleInvoiceAdditionalChargeData extends AbstractChargeData
{
    #[Max(50)]
    public ?string $Account = null;

    #[Max(1024)]
    public ?string $Comment = null;
}
