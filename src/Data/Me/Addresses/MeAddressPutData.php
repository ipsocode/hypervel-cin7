<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Me\Addresses;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\AddressType;

/**
 * The body of `me/addresses` PUT: the Me Address table with the `AddressID` of the address to
 * change, which PUT requires. The POST body is `MeAddressPostData`.
 *
 * @see docs/data.md
 */
final class MeAddressPutData extends AbstractMeAddressData
{
    public function __construct(
        string $Line1,
        string $CitySuburb,
        string $StateProvince,
        string $ZipPostCode,
        string $Country,
        AddressType $Type,
        #[Uuid]
        public string $AddressID,
    ) {
        parent::__construct($Line1, $CitySuburb, $StateProvince, $ZipPostCode, $Country, $Type);
    }
}
