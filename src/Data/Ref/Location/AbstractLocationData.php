<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Location;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The fields of the location table: the response of `ref/location` and the body of its POST and PUT.
 * Each is a final child that adds its `ID`, or none. Every location needs its `Name`, so each child
 * passes it to this constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractLocationData extends Data
{
    /**
     * @var null|list<LocationBinData>
     */
    #[DataCollectionOf(LocationBinData::class)]
    public ?array $Bins = null;

    public ?bool $IsDefault = null;

    public ?bool $Deprecated = null;

    public ?bool $IsDeprecated = null;

    public ?bool $AllowReorder = null;

    public ?bool $FixedAssetsLocation = null;

    #[Uuid]
    public ?string $ParentID = null;

    public ?string $ParentName = null;

    public ?int $ReferenceCount = null;

    #[Max(50)]
    public ?string $AddressLine1 = null;

    #[Max(50)]
    public ?string $AddressLine2 = null;

    #[Max(50)]
    public ?string $AddressCitySuburb = null;

    #[Max(50)]
    public ?string $AddressStateProvince = null;

    #[Max(50)]
    public ?string $AddressZipPostCode = null;

    #[Max(50)]
    public ?string $AddressCountry = null;

    #[Max(50)]
    public ?string $PickZones = null;

    public ?bool $IsShopfloor = null;

    public ?bool $IsCoMan = null;

    public ?bool $IsStaging = null;

    public function __construct(
        #[Max(50)]
        public string $Name,
    ) {
    }
}
