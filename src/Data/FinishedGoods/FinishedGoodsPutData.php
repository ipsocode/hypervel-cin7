<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\FinishedGoods;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * The body of `finishedGoods` PUT: the table of PUT fields, which requires only the `ID`; Cin7
 * ignores a field that cannot change in the current status. `ProductName` is in the example, not
 * the table. The POST body is `FinishedGoodsPostData`.
 *
 * @see docs/data.md
 */
final class FinishedGoodsPutData extends AbstractFinishedGoodsData
{
    public function __construct(
        #[Uuid]
        public string $ID,
        public ?string $WIPAccount = null,
        public ?string $Account = null,
        public ?float $Quantity = null,
        #[DateTime]
        public ?string $CompletionDate = null,
    ) {
    }
}
