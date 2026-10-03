<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\FinishedGoods;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Concerns\HasCustomFields;

/**
 * The fields of the Finished Goods table that every `finishedGoods` class shares, optional in
 * each: the response and the POST and PUT bodies are final children that add their own. The
 * product, the location and `WIPDate` are redeclared on the POST body, which carries their rules.
 * `Bin` is typed Decimal in the tables, copied from the field above it: it is the bin's name.
 *
 * @see docs/data.md
 */
abstract class AbstractFinishedGoodsData extends Data
{
    use HasCustomFields;

    #[Uuid]
    public ?string $ProductID = null;

    public ?string $ProductCode = null;

    public ?string $ProductName = null;

    #[Uuid]
    public ?string $LocationID = null;

    public ?string $Location = null;

    #[Uuid]
    public ?string $BinID = null;

    public ?string $Bin = null;

    #[DateTime]
    public ?string $WIPDate = null;

    public ?string $BatchSN = null;

    #[DateTime]
    public ?string $ExpiryDate = null;

    public ?string $Notes = null;
}
