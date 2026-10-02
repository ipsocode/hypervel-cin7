<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Category;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;

/**
 * The fields of the product category table: the response of `ref/category` and the body of its POST and PUT. Each
 * is a final child that adds its `ID`, or none. Every product category needs its `Name`, so each child
 * passes it to this constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractProductCategoryData extends Data
{
    public function __construct(
        #[Max(50)]
        public string $Name,
    ) {
    }
}
