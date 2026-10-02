<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Me\Addresses;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\AddressType;

/**
 * Me Address, one entry of `MeAddressesList` in every `me/addresses` response: the Me Address
 * table with its `AddressID`. The bodies of POST and PUT are `MeAddressPostData` and
 * `MeAddressPutData`.
 *
 * @see docs/data.md
 */
final class MeAddressData extends AbstractMeAddressData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $Line1,
        string $CitySuburb,
        string $StateProvince,
        string $ZipPostCode,
        string $Country,
        AddressType $Type,
        #[Uuid]
        public ?string $AddressID = null,
    ) {
        parent::__construct($Line1, $CitySuburb, $StateProvince, $ZipPostCode, $Country, $Type);
    }
}
