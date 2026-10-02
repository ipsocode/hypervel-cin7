<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Attachment;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;

/**
 * Purchase Attachments, the `{TaskID, Lines}` envelope every `purchase/attachment` action answers
 * with; `TaskID` is the purchase's ID. The POST body is `PurchaseAttachmentPostData`.
 *
 * @see docs/data.md
 */
final class PurchaseAttachmentsData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<AttachmentLineData> $Lines
     */
    public function __construct(
        #[Uuid]
        public string $TaskID,
        #[DataCollectionOf(AttachmentLineData::class)]
        public ?array $Lines = null,
    ) {
    }
}
