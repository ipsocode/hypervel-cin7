<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Tax;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
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
        #[Max(50)]
        public string $Name,
        public string $Account,
        public bool $IsActive,
        public bool $TaxInclusive,
        #[Uuid]
        #[Max(50)]
        public ?string $ID = null,
        public ?float $TaxPercent = null,
        public ?bool $IsTaxForSale = null,
        public ?bool $IsTaxForPurchase = null,
        #[DataCollectionOf(TaxComponentData::class)]
        public ?array $Components = null,
    ) {
    }
}
