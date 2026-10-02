<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Ipsocode\Cin7\Data\AbstractChargeData;

/**
 * Sale Additional Charge Model: the charge fields of `AbstractChargeData` and a `Comment`.
 *
 * @see docs/data.md
 */
final class SaleAdditionalChargeData extends AbstractChargeData
{
    public ?string $Comment = null;
}
