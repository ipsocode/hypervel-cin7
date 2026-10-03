<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Stock;

use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Purchase Stock Model, the `StockReceived` of a simple purchase, and the Available Fields for
 * Purchase Stock Received table, the response of `purchase/stock`, which adds the purchase's
 * `TaskID`. One name, so one class with the union of both: the table requires `TaskID` and the
 * purchase's `StockReceived` embeds the stock without it, so it is nullable and `#[Required]`. The
 * POST body is `PurchaseStockPostData`.
 *
 * @see docs/data.md
 */
final class PurchaseStockData extends AbstractPurchaseStockData implements WithResponse
{
    use HasResponse;

    /**
     * @param list<PurchaseStockLineData> $Lines
     */
    public function __construct(
        TaskStatus $Status,
        array $Lines,
        #[Required]
        #[Uuid]
        public ?string $TaskID = null,
    ) {
        parent::__construct($Status, $Lines);
    }
}
