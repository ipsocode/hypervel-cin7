<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTransfer;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\StockTransfer\Order\StockTransferOrderData;
use Ipsocode\Cin7\Enums\StockTransferStatus;

/**
 * Stock Transfer, the response of every `stockTransfer` action: the table with its `TaskID`, its
 * auto-generated `Number`, its `Order` and `LastModifiedOn`. The bodies of POST and PUT are
 * `StockTransferPostData` and `StockTransferPutData`.
 *
 * @see docs/data.md
 */
final class StockTransferData extends AbstractStockTransferData implements WithResponse
{
    use HasResponse;

    /**
     * @param list<StockTransferLineData> $Lines
     */
    public function __construct(
        StockTransferStatus $Status,
        string $CompletionDate,
        array $Lines,
        #[Uuid]
        public ?string $TaskID = null,
        public ?string $Number = null,
        public ?StockTransferOrderData $Order = null,
        #[DateTime]
        public ?string $LastModifiedOn = null,
    ) {
        parent::__construct($Status, $CompletionDate, $Lines);
    }
}
