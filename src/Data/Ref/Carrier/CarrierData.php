<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Carrier;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Carrier, one entry of `CarrierList`: the table with its `CarrierID`. The bodies of POST and PUT
 * are `CarrierPostData` and `CarrierPutData`.
 *
 * @see docs/data.md
 */
final class CarrierData extends AbstractCarrierData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $Description,
        #[Uuid]
        public ?string $CarrierID = null,
    ) {
        parent::__construct($Description);
    }
}
