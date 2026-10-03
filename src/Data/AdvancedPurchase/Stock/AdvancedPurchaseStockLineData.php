<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\Stock;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchaseStockLineData;

/**
 * Advanced Purchase Stock Line Model, a line of an advanced purchase's stock received. The fields
 * it shares with the purchase stock line and the put away line are
 * `AbstractPurchaseStockLineData`'s; it adds `Location` and `LocationID`, which this table, unlike
 * theirs, leaves optional. `Name` and `Received` are read-only, and the write requests leave them
 * out of the body. The `advanced-purchase` examples add the stock batch, `CardID`, which the table
 * does not list, and, as on every object with a `ProductID`, the product fields.
 *
 * @see docs/data.md
 */
final class AdvancedPurchaseStockLineData extends AbstractPurchaseStockLineData
{
    #[Max(256)]
    public ?string $Location = null;

    #[Uuid]
    public ?string $LocationID = null;
}
