<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\ManualJournal;

use Ipsocode\Cin7\Data\AbstractManualJournalLineData;

/**
 * Purchase Manual Journal Line Model, one line of a purchase's or an advanced purchase's manual
 * journal: the sale's line plus the read-only `IsSystem`, which marks a line Cin7 posted and which
 * cannot be changed or deleted.
 *
 * @see docs/data.md
 */
final class PurchaseManualJournalLineData extends AbstractManualJournalLineData
{
    public ?bool $IsSystem = null;
}
