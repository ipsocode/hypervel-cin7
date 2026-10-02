<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Tax;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Tax, one entry of `TaxRuleList` in every `ref/tax` response: the Tax table with its `ID`. The
 * bodies of POST and PUT are `TaxPostData` and `TaxPutData`.
 *
 * @see docs/data.md
 */
final class TaxData extends AbstractTaxData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $Name,
        string $Account,
        bool $IsActive,
        bool $TaxInclusive,
        #[Uuid]
        #[Max(50)]
        public ?string $ID = null,
    ) {
        parent::__construct($Name, $Account, $IsActive, $TaxInclusive);
    }
}
