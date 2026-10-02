<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * The fields of the Sale Manual Journal Line Model, which the Purchase Manual Journal Line Model
 * repeats and extends with `IsSystem`. Both tables mark `Amount`, `Date`, `Debit` and `Credit`
 * required, so each child takes them through this constructor; `Reference` is set through
 * `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractManualJournalLineData extends Data
{
    public ?string $Reference = null;

    public function __construct(
        public float $Amount,
        #[DateTime]
        public string $Date,
        public string $Debit,
        public string $Credit,
    ) {
    }
}
