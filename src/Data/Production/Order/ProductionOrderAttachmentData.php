<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * A production order attachment, the body and response of `production/order/attachment` POST and
 * the `OrderAttachments` of its GET. The reference documents no table for it: it is modelled from
 * its examples.
 *
 * @see docs/data.md
 */
final class ProductionOrderAttachmentData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public ?string $AttachmentID = null,
        public ?string $FileName = null,
        #[DateTime]
        public ?string $Date = null,
        public ?string $ContentType = null,
        public ?bool $IsProcessed = null,
        public ?string $UserName = null,
        public ?string $Content = null,
        public ?string $ExternalDownloadLink = null,
        public ?string $ExternalViewLink = null,
    ) {
    }
}
