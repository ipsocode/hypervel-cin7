<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Concerns;

use Hypervel\Data\Attributes\Validation\Max;

/**
 * `AdditionalAttribute1` to `AdditionalAttribute10`, which a customer, a product and a sale's
 * `AdditionalAttributes` carry. The Customer table documents them as one row,
 * `AdditionalAttribute#`, with no length; the Product table and the Additional Attribute Model
 * give each 256 characters, and that limit applies to all three.
 *
 * @see docs/data.md
 */
trait HasAdditionalAttributes
{
    #[Max(256)]
    public ?string $AdditionalAttribute1 = null;

    #[Max(256)]
    public ?string $AdditionalAttribute2 = null;

    #[Max(256)]
    public ?string $AdditionalAttribute3 = null;

    #[Max(256)]
    public ?string $AdditionalAttribute4 = null;

    #[Max(256)]
    public ?string $AdditionalAttribute5 = null;

    #[Max(256)]
    public ?string $AdditionalAttribute6 = null;

    #[Max(256)]
    public ?string $AdditionalAttribute7 = null;

    #[Max(256)]
    public ?string $AdditionalAttribute8 = null;

    #[Max(256)]
    public ?string $AdditionalAttribute9 = null;

    #[Max(256)]
    public ?string $AdditionalAttribute10 = null;
}
