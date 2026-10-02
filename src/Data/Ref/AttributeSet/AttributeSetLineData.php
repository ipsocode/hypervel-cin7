<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\AttributeSet;

use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\AttributeType;

/**
 * Attribute Set Line Model: one of an attribute set's ten attributes, read-only.
 *
 * @see docs/data.md
 */
final class AttributeSetLineData extends Data
{
    public function __construct(
        public ?string $Name = null,
        public ?AttributeType $Type = null,
        public ?string $Values = null,
    ) {
    }
}
