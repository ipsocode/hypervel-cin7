<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTake;

use Ipsocode\Cin7\Enums\StockTakeStatus;

/**
 * The body of `stocktake` POST: the Stock Take table without the `TaskID` and `StocktakeNumber`
 * Cin7 assigns. `Status` is required on PUT only. The PUT body is `StockTakePutData`.
 *
 * @see docs/data.md
 */
final class StockTakePostData extends AbstractStockTakeData
{
    public function __construct(
        string $EffectiveDate,
        string $Account,
        public ?StockTakeStatus $Status = null,
    ) {
        parent::__construct($EffectiveDate, $Account);
    }
}
