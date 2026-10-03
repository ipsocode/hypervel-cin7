<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Journal;

use Hypervel\Data\Data;

/**
 * Journal Line Model: the debit and credit accounts of a journal and the amount moved between
 * them, in the journal's currency and in the base currency.
 *
 * @see docs/data.md
 */
final class JournalLineData extends Data
{
    public function __construct(
        public string $Debit,
        public string $Credit,
        public float $Amount,
        public float $BaseAmount,
        public ?string $Reference = null,
    ) {
    }
}
