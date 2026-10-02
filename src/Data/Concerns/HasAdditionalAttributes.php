<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Concerns;

/**
 * `AdditionalAttribute1` to `AdditionalAttribute10`, which a customer, a product and a sale's
 * `AdditionalAttributes` carry: the reference documents them as one row, `AdditionalAttribute#`.
 *
 * @see docs/data.md
 */
trait HasAdditionalAttributes
{
    public ?string $AdditionalAttribute1 = null;

    public ?string $AdditionalAttribute2 = null;

    public ?string $AdditionalAttribute3 = null;

    public ?string $AdditionalAttribute4 = null;

    public ?string $AdditionalAttribute5 = null;

    public ?string $AdditionalAttribute6 = null;

    public ?string $AdditionalAttribute7 = null;

    public ?string $AdditionalAttribute8 = null;

    public ?string $AdditionalAttribute9 = null;

    public ?string $AdditionalAttribute10 = null;
}
