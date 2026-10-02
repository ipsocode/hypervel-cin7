<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockAdjustment;

use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * The fields of the Stock Adjustment table: the response of every `stockadjustment` action and the
 * body of its POST and PUT. Each is a final child that adds its own fields.
 *
 * Every stock adjustment needs its `EffectiveDate` and `Status`, so each child passes them to this
 * constructor; the optional fields declared here are set through `from()`. `StocktakeNumber` is
 * auto-generated, but the POST and PUT examples send it, so the bodies take it too.
 *
 * @see docs/data.md
 */
abstract class AbstractStockAdjustmentData extends Data
{
    public ?string $StocktakeNumber = null;

    public ?string $Account = null;

    public ?string $Reference = null;

    public ?string $Comment = null;

    public function __construct(
        #[DateTime]
        public string $EffectiveDate,
        public CompletionStatus $Status,
    ) {
    }
}
