<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\Stock;

use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Advanced Purchase Stock Model, one stock receiving task of an advanced purchase (an item of the
 * `StockReceiving` that every `advanced-purchase/stock` action answers with), and the Available
 * Fields for Purchase Stock Received table, which adds the purchase's `PurchaseID`. One name, so
 * one class: `TaskID` is required, as the model requires it. The table requires `PurchaseID`, but
 * the items of a `StockReceiving` never carry it (it sits on their envelope), so it is nullable,
 * for the responses, and `#[Required]`, which only a write body checks. An advanced purchase's
 * `StockReceived` items add `InvoicingAndReceivingNumber`, which only the `advanced-purchase`
 * examples send, so it is optional too. The bodies of POST and PUT are
 * `AdvancedPurchaseStockPostData` and `AdvancedPurchaseStockPutData`.
 *
 * @see docs/data.md
 */
final class AdvancedPurchaseStockData extends AbstractAdvancedPurchaseStockData
{
    /**
     * @param list<AdvancedPurchaseStockLineData> $Lines
     */
    public function __construct(
        TaskStatus $Status,
        array $Lines,
        #[Uuid]
        public string $TaskID,
        #[Required]
        #[Uuid]
        public ?string $PurchaseID = null,
        public ?int $InvoicingAndReceivingNumber = null,
    ) {
        parent::__construct($Status, $Lines);
    }
}
