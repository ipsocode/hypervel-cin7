<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Brand;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of `ref/brand` PUT: the table with the `ID` of the brand to change, which PUT requires.
 * The POST body is `BrandPostData`.
 *
 * @see docs/data.md
 */
final class BrandPutData extends AbstractBrandData
{
    public function __construct(
        string $Name,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($Name);
    }
}
