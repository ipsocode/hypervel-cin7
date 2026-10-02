<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * How the `sale` GET returns the country of an address: as its name (`Regular`, Cin7's default)
 * or as a country code (`Code2` or `Code`; the reference does not say which codes).
 *
 * @see docs/data.md
 */
enum CountryFormat: string
{
    case Regular = 'Regular';
    case Code2 = 'Code2';
    case Code = 'Code';
}
