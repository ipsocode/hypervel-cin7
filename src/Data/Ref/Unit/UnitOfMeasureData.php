<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Unit;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Unit of measure, one entry of `UnitList` in a `ref/unit` list and the response of its POST and PUT: the table with
 * its `ID`. The bodies of POST and PUT are `UnitOfMeasurePostData` and `UnitOfMeasurePutData`.
 *
 * @see docs/data.md
 */
final class UnitOfMeasureData extends AbstractUnitOfMeasureData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $Name,
        #[Uuid]
        public ?string $ID = null,
    ) {
        parent::__construct($Name);
    }
}
