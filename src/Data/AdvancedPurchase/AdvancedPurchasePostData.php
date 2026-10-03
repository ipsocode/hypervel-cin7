<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Data\AbstractPurchaseData;
use Ipsocode\Cin7\Enums\ProcessType;

/**
 * The body of `advanced-purchase` POST: the Purchase POST/PUT Attributes without the `ID` only PUT
 * takes, with the POST-only `PurchaseType` (`Simple` or `Advanced`, a `ProcessType`, as the sale's
 * `SaleType`) and `IsServiceOnly`, which the simple purchase's table does not have. The PUT body is
 * `AdvancedPurchasePutData`, and the response `AdvancedPurchaseData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchasePostData extends AbstractPurchaseData
{
    public function __construct(
        string $Location,
        #[Max(10)]
        public string $Approach,
        public ?ProcessType $PurchaseType = null,
        public ?bool $IsServiceOnly = null,
    ) {
        parent::__construct($Location);
    }
}
