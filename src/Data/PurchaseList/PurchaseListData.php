<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\PurchaseList;

use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\AbstractPurchaseListData;

/**
 * Purchase List, one entry of `PurchaseList` in a `purchaseList` response. Its table is the
 * Purchase Credit Note List's, field for field, so every field is in the parent.
 *
 * @see docs/data.md
 */
final class PurchaseListData extends AbstractPurchaseListData implements WithResponse
{
    use HasResponse;
}
