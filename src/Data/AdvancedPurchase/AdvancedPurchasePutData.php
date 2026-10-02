<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchaseData;

/**
 * The body of `advanced-purchase` PUT: the Purchase POST/PUT Attributes with the `ID` PUT requires
 * and `IsServiceOnly`, without the POST-only `PurchaseType`. The table requires `Approach`, but the
 * PUT example sends none (the purchase keeps the one it has), so it is optional here. The POST body
 * is `AdvancedPurchasePostData`, and the response `AdvancedPurchaseData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchasePutData extends AbstractPurchaseData
{
    public function __construct(
        string $Location,
        #[Uuid]
        public string $ID,
        #[Max(10)]
        public ?string $Approach = null,
        public ?bool $IsServiceOnly = null,
    ) {
        parent::__construct($Location);
    }
}
