<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * The attachments of a production order, the answer of `production/order/attachment` GET: those of
 * the order, of its operations and of their resources. The reference documents no table for it: it
 * is modelled from its example.
 *
 * @see docs/data.md
 */
final class ProductionOrderAttachmentsData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<ProductionOrderAttachmentData> $OrderAttachments
     * @param null|list<ProductionOrderOperationAttachmentData> $OrderOperationAttachments
     * @param null|list<mixed> $OrderOperationResourceAttachments
     */
    public function __construct(
        #[Uuid]
        public ?string $ProductionOrderID = null,
        #[DataCollectionOf(ProductionOrderAttachmentData::class)]
        public ?array $OrderAttachments = null,
        #[DataCollectionOf(ProductionOrderOperationAttachmentData::class)]
        public ?array $OrderOperationAttachments = null,
        public ?array $OrderOperationResourceAttachments = null,
    ) {
    }
}
