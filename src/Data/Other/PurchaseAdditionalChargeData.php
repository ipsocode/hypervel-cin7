<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Other;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Data\AbstractChargeData;

/**
 * Purchase Additional Charge Model, an additional charge of a purchase order, which the simple and
 * the advanced purchase both carry: the charge fields of `AbstractChargeData`, a `Reference`, and
 * the line `Total`, which this table, unlike the sale ones, requires.
 *
 * @see docs/data.md
 */
final class PurchaseAdditionalChargeData extends AbstractChargeData
{
    #[Max(256)]
    public ?string $Reference = null;

    public function __construct(
        string $Description,
        float $Quantity,
        float $Price,
        float $Tax,
        string $TaxRule,
        public float $Total,
    ) {
        parent::__construct($Description, $Quantity, $Price, $Tax, $TaxRule);
    }
}
