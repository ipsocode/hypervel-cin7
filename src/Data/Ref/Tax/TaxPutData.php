<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Tax;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of `ref/tax` PUT: the Tax table with the `ID` of the tax rule to change, which PUT
 * requires. The POST body is `TaxPostData`.
 *
 * @see docs/data.md
 */
final class TaxPutData extends AbstractTaxData
{
    public function __construct(
        string $Name,
        string $Account,
        bool $IsActive,
        bool $TaxInclusive,
        #[Uuid]
        #[Max(50)]
        public string $ID,
    ) {
        parent::__construct($Name, $Account, $IsActive, $TaxInclusive);
    }
}
