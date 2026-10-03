<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Category;

/**
 * The body of `ref/category` POST: the table without the `ID` Cin7 ignores on POST. The PUT body is
 * `ProductCategoryPutData`.
 *
 * @see docs/data.md
 */
final class ProductCategoryPostData extends AbstractProductCategoryData
{
}
