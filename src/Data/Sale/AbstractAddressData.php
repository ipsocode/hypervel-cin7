<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The fields of the Address Model a Sale bills to, which the Sale Shipping Address extends with
 * `Company`, `Contact` and `ShipToOther`. Both tables mark `Line1` and `Country` required, so
 * each child takes them through this constructor; the optional fields are set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractAddressData extends Data
{
    #[Uuid]
    public ?string $ID = null;

    #[Max(256)]
    public ?string $DisplayAddressLine1 = null;

    #[Max(256)]
    public ?string $DisplayAddressLine2 = null;

    #[Max(256)]
    public ?string $Line2 = null;

    #[Max(256)]
    public ?string $City = null;

    #[Max(256)]
    public ?string $State = null;

    #[Max(20)]
    public ?string $Postcode = null;

    public function __construct(
        #[Max(256)]
        public string $Line1,
        #[Max(256)]
        public string $Country,
    ) {
    }
}
