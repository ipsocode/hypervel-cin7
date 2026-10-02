<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Journal;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * The body of `journal` PUT: the Journal table with the `TaskID` of the journal to change, which
 * PUT requires. The POST body is `JournalPostData`.
 *
 * @see docs/data.md
 */
final class JournalPutData extends AbstractJournalData
{
    public function __construct(
        CompletionStatus $Status,
        string $Currency,
        float $CurrencyConversionRate,
        string $EffectiveDate,
        #[Uuid]
        public string $TaskID,
    ) {
        parent::__construct($Status, $Currency, $CurrencyConversionRate, $EffectiveDate);
    }
}
