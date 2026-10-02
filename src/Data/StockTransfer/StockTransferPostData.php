<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTransfer;

/**
 * The body of `stockTransfer` POST: the Stock Transfer table without the `TaskID`, `Number`,
 * `Order` and `LastModifiedOn` Cin7 assigns. The PUT body is `StockTransferPutData`.
 *
 * @see docs/data.md
 */
final class StockTransferPostData extends AbstractStockTransferData
{
}
