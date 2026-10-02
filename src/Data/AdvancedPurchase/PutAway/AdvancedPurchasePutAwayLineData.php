<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\PutAway;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Data\AbstractPurchaseStockLineData;

/**
 * Advanced Purchase Put Away Line Model, a line of an advanced purchase's put away. It repeats the
 * Purchase Stock Line Model field for field: the fields it shares with the stock lines are
 * `AbstractPurchaseStockLineData`'s, and it adds `Location` and `LocationID`, as a line needs one
 * of them (`#[RequiredWithout]`). `Name` and `Received` are read-only, and the write request leaves
 * them out of the body. Like every object with a `ProductID`, it carries the product fields, which
 * the `advanced-purchase` examples send.
 *
 * @see docs/data.md
 */
final class AdvancedPurchasePutAwayLineData extends AbstractPurchaseStockLineData
{
    #[Max(256)]
    #[RequiredWithout('LocationID')]
    public ?string $Location = null;

    #[Uuid]
    #[RequiredWithout('Location')]
    public ?string $LocationID = null;
}
