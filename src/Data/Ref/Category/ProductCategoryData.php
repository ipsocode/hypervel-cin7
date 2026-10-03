<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Category;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Product category, one entry of `CategoryList` in a `ref/category` list and the response of its POST and PUT: the table with
 * its `ID`. The bodies of POST and PUT are `ProductCategoryPostData` and `ProductCategoryPutData`.
 *
 * @see docs/data.md
 */
final class ProductCategoryData extends AbstractProductCategoryData implements WithResponse
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
