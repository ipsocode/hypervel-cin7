<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedPurchase\PutAway;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Advanced Purchase Put Away, the `{PurchaseID, PutAway}` envelope every
 * `advanced-purchase/put-away` action answers with: the purchase's put away tasks. No table
 * documents it, only the examples, so `PutAway` is optional.
 *
 * @see docs/data.md
 */
final class AdvancedPurchasePutAwaysData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<AdvancedPurchasePutAwayData> $PutAway
     */
    public function __construct(
        #[Uuid]
        public string $PurchaseID,
        #[DataCollectionOf(AdvancedPurchasePutAwayData::class)]
        public ?array $PutAway = null,
    ) {
    }
}
