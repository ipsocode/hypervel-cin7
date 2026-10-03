<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\PurchaseCreditNoteList;

use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\AbstractPurchaseListData;

/**
 * Purchase Credit Note List, one entry of `PurchaseList` in a `purchaseCreditNoteList` response:
 * the example keys the list `PurchaseList`, as `purchaseList` does. Its table is the Purchase
 * List's, field for field, but lists two more types, `Purchase Credit Note` and `Credit Note`,
 * which `PurchaseType` has; so every field is in the parent.
 *
 * @see docs/data.md
 */
final class PurchaseCreditNoteListData extends AbstractPurchaseListData implements WithResponse
{
    use HasResponse;
}
