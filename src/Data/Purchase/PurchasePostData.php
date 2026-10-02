<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Data\AbstractPurchaseData;

/**
 * The body of `purchase` POST: the Purchase POST/PUT Attributes without the `ID` only PUT takes.
 * The PUT body is `PurchasePutData`, and the response `PurchaseData`.
 *
 * @see docs/data.md
 */
final class PurchasePostData extends AbstractPurchaseData
{
    public function __construct(
        string $Location,
        #[Max(10)]
        public string $Approach,
    ) {
        parent::__construct($Location);
    }
}
