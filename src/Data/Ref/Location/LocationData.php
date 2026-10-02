<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Location;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Location, one entry of `LocationList` in a `ref/location` list and the response of its POST and
 * PUT: the table with its `ID`. The bodies of POST and PUT are `LocationPostData` and
 * `LocationPutData`.
 *
 * @see docs/data.md
 */
final class LocationData extends AbstractLocationData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $Name,
        #[Uuid]
        public ?string $ID = null,
    ) {
        parent::__construct($Name);
    }
}
