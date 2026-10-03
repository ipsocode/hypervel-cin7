<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\Order;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * The production orders a `production/order` action answers with, under `ProductionOrders`, and
 * the `Warning` a release adds.
 *
 * @see docs/data.md
 */
final class ProductionOrdersData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<ProductionOrderData> $ProductionOrders
     */
    public function __construct(
        public ?string $Warning = null,
        #[DataCollectionOf(ProductionOrderData::class)]
        public ?array $ProductionOrders = null,
    ) {
    }
}
