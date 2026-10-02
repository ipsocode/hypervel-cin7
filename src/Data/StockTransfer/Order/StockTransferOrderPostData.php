<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTransfer\Order;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\StockTransferOrderStatus;

/**
 * The body of `stockTransfer/order` POST: the Stock Transfer Order table, which requires the
 * `TaskID` of the transfer, the `Status` and the `Lines`. The response is `StockTransferOrderData`.
 *
 * @see docs/data.md
 */
final class StockTransferOrderPostData extends Data
{
    /**
     * @param list<StockTransferOrderLineData> $Lines
     */
    public function __construct(
        #[Uuid]
        public string $TaskID,
        public StockTransferOrderStatus $Status,
        #[DataCollectionOf(StockTransferOrderLineData::class)]
        public array $Lines,
        #[DateTime]
        public ?string $LastModifiedOn = null,
    ) {
    }
}
