<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTransfer;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\StockTransferStatus;

/**
 * The body of `stockTransfer` PUT: the Stock Transfer table with the `TaskID` PUT requires. The
 * POST body is `StockTransferPostData`.
 *
 * @see docs/data.md
 */
final class StockTransferPutData extends AbstractStockTransferData
{
    /**
     * @param list<StockTransferLineData> $Lines
     */
    public function __construct(
        StockTransferStatus $Status,
        string $CompletionDate,
        array $Lines,
        #[Uuid]
        public string $TaskID,
    ) {
        parent::__construct($Status, $CompletionDate, $Lines);
    }
}
