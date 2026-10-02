<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Brand;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Brand, one entry of `BrandList` in a `ref/brand` list and the response of its POST and PUT: the table with
 * its `ID`. The bodies of POST and PUT are `BrandPostData` and `BrandPutData`.
 *
 * @see docs/data.md
 */
final class BrandData extends AbstractBrandData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $Name,
        #[Uuid]
        public ?string $ID = null,
    ) {
        parent::__construct($Name);
    }
}
