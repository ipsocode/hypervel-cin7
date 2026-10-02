<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Tax;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Tax, one entry of `TaxRuleList` and the body of `ref/tax` POST and PUT.
 *
 * @see docs/data.md
 */
final class TaxData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param list<TaxComponentData>|Optional $Components
     */
    public function __construct(
        public string|Optional $ID,
        public string|Optional $Name,
        public string|Optional $Account,
        public bool|Optional $IsActive,
        public bool|Optional $TaxInclusive,
        public float|Optional $TaxPercent,
        public bool|Optional $IsTaxForSale,
        public bool|Optional $IsTaxForPurchase,
        #[DataCollectionOf(TaxComponentData::class)]
        public array|Optional $Components,
    ) {
    }
}
