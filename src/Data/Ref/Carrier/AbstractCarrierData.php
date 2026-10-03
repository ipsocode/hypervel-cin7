<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Carrier;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;

/**
 * The fields of the carrier table: the response of `ref/carrier` and the body of its POST and PUT.
 * Each is a final child that adds its `CarrierID`, or none. Every carrier needs its `Description`,
 * so each child passes it to this constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractCarrierData extends Data
{
    public function __construct(
        #[Max(256)]
        public string $Description,
    ) {
    }
}
