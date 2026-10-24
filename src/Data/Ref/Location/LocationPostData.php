<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Location;

/**
 * The body of `ref/location` POST: the table without the `ID` Cin7 ignores on POST. The PUT body is
 * `LocationPutData`.
 *
 * @see src/docs/data.md
 */
final class LocationPostData extends AbstractLocationData
{
}
