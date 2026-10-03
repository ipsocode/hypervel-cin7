<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\PriceTier;

use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Price Tier, one entry of `PriceTiers`: a tier's code, 1 to 10, and its name. A product's
 * `PriceTiers` is a different thing, a map of these names to prices.
 *
 * @see docs/data.md
 */
final class PriceTierData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        public ?int $Code = null,
        public ?string $Name = null,
    ) {
    }
}
