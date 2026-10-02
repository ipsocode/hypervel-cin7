<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Additional Attribute Model.
 *
 * @see docs/data.md
 */
final class AdditionalAttributeData extends Data
{
    public function __construct(
        public string|Optional $AdditionalAttribute1,
        public string|Optional $AdditionalAttribute2,
        public string|Optional $AdditionalAttribute3,
        public string|Optional $AdditionalAttribute4,
        public string|Optional $AdditionalAttribute5,
        public string|Optional $AdditionalAttribute6,
        public string|Optional $AdditionalAttribute7,
        public string|Optional $AdditionalAttribute8,
        public string|Optional $AdditionalAttribute9,
        public string|Optional $AdditionalAttribute10,
    ) {
    }
}
