<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Category;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of `ref/category` PUT: the table with the `ID` of the product category to change, which PUT requires.
 * The POST body is `ProductCategoryPostData`.
 *
 * @see docs/data.md
 */
final class ProductCategoryPutData extends AbstractProductCategoryData
{
    public function __construct(
        string $Name,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($Name);
    }
}
