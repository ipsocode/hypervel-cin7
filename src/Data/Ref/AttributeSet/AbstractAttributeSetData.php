<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\AttributeSet;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Concerns\HasAttributeSetAttributes;

/**
 * The fields of the Attribute Set table: the response of `ref/attributeset` and the body of its
 * POST and PUT. Each is a final child that adds its `Attribute1…` fields and `ID`.
 *
 * Every attribute set needs its `Name`, so each child passes it to this constructor. A write also
 * requires the first attribute's name, type and values, which each write class takes itself; the
 * response leaves them optional. The reference writes the other nine as `Attribute#Name`,
 * `Attribute#Type` and `Attribute#Values`, for # from 1 to 10.
 *
 * @see docs/data.md
 */
abstract class AbstractAttributeSetData extends Data
{
    use HasAttributeSetAttributes;

    public function __construct(
        #[Max(50)]
        public string $Name,
    ) {
    }
}
