<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTransfer\Order;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\StockTransferOrderStatus;

/**
 * Stock Transfer Order, the response of `stockTransfer/order` and the `Order` of a stock transfer:
 * the `Status` of the order and its `Lines`. The nested order carries no `TaskID`. The body of the
 * POST is `StockTransferOrderPostData`.
 *
 * @see docs/data.md
 */
final class StockTransferOrderData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<StockTransferOrderLineData> $Lines
     */
    public function __construct(
        public StockTransferOrderStatus $Status,
        #[DataCollectionOf(StockTransferOrderLineData::class)]
        public ?array $Lines = null,
        #[Uuid]
        public ?string $TaskID = null,
        #[DateTime]
        public ?string $LastModifiedOn = null,
    ) {
    }
}
