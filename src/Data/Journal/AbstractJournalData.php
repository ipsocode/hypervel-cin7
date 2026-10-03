<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Journal;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * The fields of the Journal table: the response of `journal` and the body of its POST and PUT.
 * Each is a final child that adds its `TaskID`, or none.
 *
 * Every journal needs its `Status`, `Currency`, `CurrencyConversionRate` and `EffectiveDate`, so
 * each child passes them to this constructor; the optional fields declared here are set through
 * `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractJournalData extends Data
{
    public ?string $Narration = null;

    public ?string $Notes = null;

    /**
     * @var null|list<JournalLineData>
     */
    #[DataCollectionOf(JournalLineData::class)]
    public ?array $Lines = null;

    public function __construct(
        public CompletionStatus $Status,
        public string $Currency,
        public float $CurrencyConversionRate,
        #[DateTime]
        public string $EffectiveDate,
    ) {
    }
}
