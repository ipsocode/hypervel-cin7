<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Tax;

/**
 * The body of `ref/tax` POST: the Tax table without the `ID` Cin7 assigns. The PUT body is
 * `TaxPutData`.
 *
 * @see docs/data.md
 */
final class TaxPostData extends AbstractTaxData
{
    public function __construct(
        string $Name,
        string $Account,
        bool $IsActive,
        bool $TaxInclusive,
    ) {
        parent::__construct($Name, $Account, $IsActive, $TaxInclusive);
    }
}
