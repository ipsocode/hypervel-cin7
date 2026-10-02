<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Me\Addresses;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\AddressType;

/**
 * The fields of the Me Address table: the response of `me/addresses` and the body of its POST and
 * PUT. Each is a final child that adds its `AddressID`, or none.
 *
 * Every address needs its `Line1`, `CitySuburb`, `StateProvince`, `ZipPostCode`, `Country` and
 * `Type`, so each child passes them to this constructor; the optional fields declared here are
 * set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractMeAddressData extends Data
{
    #[Max(256)]
    public ?string $Line2 = null;

    public ?bool $DefaultForType = null;

    public function __construct(
        #[Max(256)]
        public string $Line1,
        #[Max(256)]
        public string $CitySuburb,
        #[Max(256)]
        public string $StateProvince,
        #[Max(20)]
        public string $ZipPostCode,
        public string $Country,
        public AddressType $Type,
    ) {
    }
}
