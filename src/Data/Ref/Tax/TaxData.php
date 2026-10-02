<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Tax;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
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
     * @param null|list<TaxComponentData> $Components
     */
    public function __construct(
        public ?string $ID = null,
        public ?string $Name = null,
        public ?string $Account = null,
        public ?bool $IsActive = null,
        public ?bool $TaxInclusive = null,
        public ?float $TaxPercent = null,
        public ?bool $IsTaxForSale = null,
        public ?bool $IsTaxForPurchase = null,
        #[DataCollectionOf(TaxComponentData::class)]
        public ?array $Components = null,
    ) {
    }
}
