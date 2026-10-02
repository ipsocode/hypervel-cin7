<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Attachment;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\AttachmentLineData;

/**
 * Sale Attachments, the `{SaleID, Lines}` envelope every `sale/attachment` action answers with.
 * The POST body is `SaleAttachmentPostData`.
 *
 * @see docs/data.md
 */
final class SaleAttachmentsData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<AttachmentLineData> $Lines
     */
    public function __construct(
        #[Uuid]
        public string $SaleID,
        #[DataCollectionOf(AttachmentLineData::class)]
        public ?array $Lines = null,
    ) {
    }
}
