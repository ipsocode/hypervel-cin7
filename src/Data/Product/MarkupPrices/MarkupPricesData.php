<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Product\MarkupPrices;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Markup Prices Model: a product and the markup of each of its price tiers. The response of GET and
 * PUT, and the body of PUT. In a PUT, a line for a tier that has none creates it, one that has
 * changes it, and one with `MarkupType::Deleted` deletes it.
 *
 * @see docs/data.md
 */
final class MarkupPricesData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param list<MarkupPriceLineData> $MarkupPrices
     */
    public function __construct(
        #[Uuid]
        public string $ProductID,
        #[DataCollectionOf(MarkupPriceLineData::class)]
        public array $MarkupPrices,
    ) {
    }
}
