<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\Stock;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Advanced Purchase Stock Received, the `{PurchaseID, StockReceiving}` envelope every
 * `advanced-purchase/stock` action answers with: the purchase's stock receiving tasks. No table
 * documents it, only the examples, so `StockReceiving` is optional.
 *
 * @see docs/data.md
 */
final class AdvancedPurchaseStocksData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<AdvancedPurchaseStockData> $StockReceiving
     */
    public function __construct(
        #[Uuid]
        public string $PurchaseID,
        #[DataCollectionOf(AdvancedPurchaseStockData::class)]
        public ?array $StockReceiving = null,
    ) {
    }
}
