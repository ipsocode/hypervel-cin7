<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTake;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\StockTakeStatus;

/**
 * The body of `stocktake` PUT: the Stock Take table with the `TaskID` and `Status` PUT requires.
 * Changing `Status` from `DRAFT` to `IN PROGRESS` fills `NonZeroStockOnHandProducts` with the
 * filtered products that have stock. The POST body is `StockTakePostData`.
 *
 * @see docs/data.md
 */
final class StockTakePutData extends AbstractStockTakeData
{
    public function __construct(
        string $EffectiveDate,
        string $Account,
        #[Uuid]
        public string $TaskID,
        public StockTakeStatus $Status,
    ) {
        parent::__construct($EffectiveDate, $Account);
    }
}
