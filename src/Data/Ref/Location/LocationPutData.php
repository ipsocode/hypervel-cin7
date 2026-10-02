<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Location;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of `ref/location` PUT: the table with the `ID` of the location to change, which PUT
 * requires. The POST body is `LocationPostData`.
 *
 * @see docs/data.md
 */
final class LocationPutData extends AbstractLocationData
{
    public function __construct(
        string $Name,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($Name);
    }
}
