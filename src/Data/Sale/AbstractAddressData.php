<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * The fields of the Address Model a Sale bills to, which the Sale Shipping Address extends with
 * `Company`, `Contact` and `ShipToOther`. A field declared here is set through `from()`, not the
 * child's constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractAddressData extends Data
{
    public ?string $ID = null;

    public ?string $DisplayAddressLine1 = null;

    public ?string $DisplayAddressLine2 = null;

    public ?string $Line1 = null;

    public ?string $Line2 = null;

    public ?string $City = null;

    public ?string $State = null;

    public ?string $Postcode = null;

    public ?string $Country = null;
}
