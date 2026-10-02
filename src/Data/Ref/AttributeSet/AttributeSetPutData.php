<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\AttributeSet;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\AttributeType;

/**
 * The body of `ref/attributeset` PUT: the table with the `ID` of the set to change, which PUT
 * requires, and the first attribute required. The POST body is `AttributeSetPostData`.
 *
 * @see docs/data.md
 */
final class AttributeSetPutData extends AbstractAttributeSetData
{
    public function __construct(
        string $Name,
        #[Max(50)]
        public string $Attribute1Name,
        public AttributeType $Attribute1Type,
        public string $Attribute1Values,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($Name);
    }
}
