<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Ipsocode\Cin7\Data\AbstractChargeData;

/**
 * Sale Invoice Additional Charge Model, also used by credit notes: the charge fields of
 * `AbstractChargeData`, a `Comment` and the revenue `Account`.
 *
 * @see docs/data.md
 */
final class SaleInvoiceAdditionalChargeData extends AbstractChargeData
{
    public ?string $Account = null;

    public ?string $Comment = null;
}
