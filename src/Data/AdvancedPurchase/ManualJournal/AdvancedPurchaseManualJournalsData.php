<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Available field for Purchase Manual Journal, the `{PurchaseID, ManualJournals}` envelope
 * `advanced-purchase/manualJournal` answers its GET and POST with: an advanced purchase's manual
 * journals, each an `AdvancedPurchasePartialManualJournalData`. The table requires both fields. The
 * POST body is `AdvancedPurchasePartialManualJournalPostData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchaseManualJournalsData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param list<AdvancedPurchasePartialManualJournalData> $ManualJournals
     */
    public function __construct(
        #[Uuid]
        public string $PurchaseID,
        #[DataCollectionOf(AdvancedPurchasePartialManualJournalData::class)]
        public array $ManualJournals,
    ) {
    }
}
