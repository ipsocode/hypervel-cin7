<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\ManualJournal;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The body of `sale/manualJournal` POST: the Sale Manual Journal table with the `SaleID` it
 * requires and a `Status` of `DRAFT` or `AUTHORISED`. The response is `SaleManualJournalData`.
 *
 * @see docs/data.md
 */
final class SaleManualJournalPostData extends AbstractSaleManualJournalData
{
    public function __construct(
        #[In(TaskStatus::Draft, TaskStatus::Authorised)]
        public TaskStatus $Status,
        #[Uuid]
        public string $SaleID,
    ) {
        parent::__construct($Status);
    }
}
