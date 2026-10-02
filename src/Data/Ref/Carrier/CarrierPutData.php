<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Carrier;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of `ref/carrier` PUT: the table with the `CarrierID` of the carrier to change, which PUT
 * requires. The POST body is `CarrierPostData`.
 *
 * @see docs/data.md
 */
final class CarrierPutData extends AbstractCarrierData
{
    public function __construct(
        string $Description,
        #[Uuid]
        public string $CarrierID,
    ) {
        parent::__construct($Description);
    }
}
