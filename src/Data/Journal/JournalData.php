<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Journal;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * Journal, one entry of `Journals` in every `journal` response: the Journal table with its
 * `TaskID`, its read-only `JournalNumber` and its `Attachments`. The bodies of POST and PUT are
 * `JournalPostData` and `JournalPutData`.
 *
 * @see docs/data.md
 */
final class JournalData extends AbstractJournalData implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<AttachmentLineData> $Attachments
     */
    public function __construct(
        CompletionStatus $Status,
        string $Currency,
        float $CurrencyConversionRate,
        string $EffectiveDate,
        #[Uuid]
        public ?string $TaskID = null,
        public ?string $JournalNumber = null,
        #[DataCollectionOf(AttachmentLineData::class)]
        public ?array $Attachments = null,
    ) {
        parent::__construct($Status, $Currency, $CurrencyConversionRate, $EffectiveDate);
    }
}
