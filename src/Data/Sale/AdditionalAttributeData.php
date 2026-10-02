<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * Additional Attribute Model.
 *
 * @see docs/data.md
 */
final class AdditionalAttributeData extends Data
{
    public function __construct(
        public ?string $AdditionalAttribute1 = null,
        public ?string $AdditionalAttribute2 = null,
        public ?string $AdditionalAttribute3 = null,
        public ?string $AdditionalAttribute4 = null,
        public ?string $AdditionalAttribute5 = null,
        public ?string $AdditionalAttribute6 = null,
        public ?string $AdditionalAttribute7 = null,
        public ?string $AdditionalAttribute8 = null,
        public ?string $AdditionalAttribute9 = null,
        public ?string $AdditionalAttribute10 = null,
    ) {
    }
}
