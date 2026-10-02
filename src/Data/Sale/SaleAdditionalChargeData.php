<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Data\AbstractChargeData;

/**
 * Sale Additional Charge Model: the charge fields of `AbstractChargeData` and a `Comment`.
 *
 * @see docs/data.md
 */
final class SaleAdditionalChargeData extends AbstractChargeData
{
    #[Max(1024)]
    public ?string $Comment = null;
}
