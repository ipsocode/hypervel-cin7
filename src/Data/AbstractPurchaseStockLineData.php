<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Concerns\HasProductFields;

/**
 * The fields the Purchase Stock Line Model, the Advanced Purchase Stock Line Model and the
 * Advanced Purchase Put Away Line Model share, with the same types and lengths in every table: a
 * line of a purchase's stock received, of an advanced purchase's stock received and of its put
 * away. With them come the product fields every object with a `ProductID` carries, and the stock
 * batch, `CardID`, which the Advanced Purchase Stock Line Model does not list but the
 * `advanced-purchase` examples send. `ProductID` and `SKU` are a bare `Yes*`, so optional; `Name`
 * and `Received` are read-only. The children span the purchase and advanced purchase families, so
 * this parent is in `src/Data/` itself.
 *
 * Every one of those tables marks `Date` and `Quantity` required, so each child takes them through
 * this constructor; the optional fields are set through `from()`. Each child declares `Location`
 * and `LocationID` itself: the Purchase Stock and Advanced Purchase Put Away Line Models require
 * one of them, and the Advanced Purchase Stock Line Model neither.
 *
 * @see docs/data.md
 */
abstract class AbstractPurchaseStockLineData extends Data
{
    use HasProductFields;

    #[Uuid]
    public ?string $ProductID = null;

    #[Max(50)]
    public ?string $SKU = null;

    #[Max(1024)]
    public ?string $Name = null;

    public ?bool $Received = null;

    #[Max(50)]
    public ?string $BatchSN = null;

    #[Max(50)]
    public ?string $SupplierSKU = null;

    #[DateTime]
    public ?string $ExpiryDate = null;

    #[Uuid]
    public ?string $CardID = null;

    public function __construct(
        #[DateTime]
        public string $Date,
        public float $Quantity,
    ) {
    }
}
