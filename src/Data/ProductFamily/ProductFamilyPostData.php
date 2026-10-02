<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\ProductFamily;

/**
 * The body of `productFamily` POST: the Product Family table without the `ID` Cin7 ignores on POST
 * and the read-only fields. The PUT body is `ProductFamilyPutData`.
 *
 * @see docs/data.md
 */
final class ProductFamilyPostData extends AbstractProductFamilyData
{
}
