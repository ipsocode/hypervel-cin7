<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Brand;

/**
 * The body of `ref/brand` POST: the table without the `ID` Cin7 ignores on POST. The PUT body is
 * `BrandPutData`.
 *
 * @see docs/data.md
 */
final class BrandPostData extends AbstractBrandData
{
}
