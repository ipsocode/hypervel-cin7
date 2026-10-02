<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchaseData;

/**
 * The body of `purchase` PUT: the Purchase POST/PUT Attributes with the `ID` PUT requires. The
 * POST body is `PurchasePostData`, and the response `PurchaseData`.
 *
 * @see docs/data.md
 */
final class PurchasePutData extends AbstractPurchaseData
{
    public function __construct(
        string $Location,
        #[Uuid]
        public string $ID,
        #[Max(10)]
        public string $Approach,
    ) {
        parent::__construct($Location);
    }
}
