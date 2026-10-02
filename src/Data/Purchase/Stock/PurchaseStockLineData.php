<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Stock;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchaseStockLineData;

/**
 * Purchase Stock Line Model, a line of a simple purchase's `StockReceived` and of
 * `purchase/stock`: the items received on a date, at a location. The fields it shares with the
 * advanced purchase's stock and put away lines are `AbstractPurchaseStockLineData`'s; it adds
 * `Location` and `LocationID`, each required if the other is empty (`#[RequiredWithout]`).
 *
 * @see docs/data.md
 */
final class PurchaseStockLineData extends AbstractPurchaseStockLineData
{
    #[Max(256)]
    #[RequiredWithout('LocationID')]
    public ?string $Location = null;

    #[Uuid]
    #[RequiredWithout('Location')]
    public ?string $LocationID = null;
}
