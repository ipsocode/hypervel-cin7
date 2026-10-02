<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\AttributeSet;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Enums\AttributeType;

/**
 * The body of `ref/attributeset` POST: the table without the `ID` Cin7 ignores on POST, and with
 * the first attribute required. The PUT body is `AttributeSetPutData`.
 *
 * @see docs/data.md
 */
final class AttributeSetPostData extends AbstractAttributeSetData
{
    public function __construct(
        string $Name,
        #[Max(50)]
        public string $Attribute1Name,
        public AttributeType $Attribute1Type,
        public string $Attribute1Values,
    ) {
        parent::__construct($Name);
    }
}
