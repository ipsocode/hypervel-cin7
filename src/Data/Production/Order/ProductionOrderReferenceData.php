<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * The reference data of a production order, the answer of `production/order/referenceData`: the
 * tags, accounts, shop floor locations, logistics paths, work centers, price tiers and suspend
 * reasons a production order can use. The reference documents no model for them: each is a list of
 * what the example shows.
 *
 * @see docs/data.md
 */
final class ProductionOrderReferenceData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<mixed> $ProductionOrderTags
     * @param null|list<mixed> $ChartOfAccounts
     * @param null|list<mixed> $ShopFloorLocations
     * @param null|list<mixed> $LogisticsPaths
     * @param null|list<mixed> $WorkCenters
     * @param null|list<mixed> $PriceTiers
     * @param null|list<mixed> $SuspendReasons
     */
    public function __construct(
        public ?array $ProductionOrderTags = null,
        public ?array $ChartOfAccounts = null,
        public ?array $ShopFloorLocations = null,
        public ?array $LogisticsPaths = null,
        public ?array $WorkCenters = null,
        public ?array $PriceTiers = null,
        public ?string $InventoryControlAccount = null,
        public ?string $WipAccount = null,
        public ?array $SuspendReasons = null,
    ) {
    }
}
