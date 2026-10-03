<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\CustomPrices;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Other\ProductPriceData;

/**
 * The body of `custom-prices` POST and PUT: the `CustomPrices` to create or change, each a
 * `ProductPriceData`. The reference has no table of its own for it, and the two bodies are the same.
 *
 * @see docs/data.md
 */
final class CustomPricesData extends Data
{
    /**
     * @param list<ProductPriceData> $CustomPrices
     */
    public function __construct(
        #[DataCollectionOf(ProductPriceData::class)]
        public array $CustomPrices,
    ) {
    }
}
