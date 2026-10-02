<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Me\Addresses;

use Ipsocode\Cin7\Enums\AddressType;

/**
 * The body of `me/addresses` POST: the Me Address table without the `AddressID` Cin7 assigns.
 * The PUT body is `MeAddressPutData`.
 *
 * @see docs/data.md
 */
final class MeAddressPostData extends AbstractMeAddressData
{
    public function __construct(
        string $Line1,
        string $CitySuburb,
        string $StateProvince,
        string $ZipPostCode,
        string $Country,
        AddressType $Type,
    ) {
        parent::__construct($Line1, $CitySuburb, $StateProvince, $ZipPostCode, $Country, $Type);
    }
}
