<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * ProductionBOMOperationLink, a link from a BOM operation to a related one, by
 * `RelatedOperationID` or `RelatedOperationName`, one of which a write body needs; `RelationType`
 * is `1`. `ID` is required when updating.
 *
 * @see docs/data.md
 */
final class ProductionBomOperationLinkData extends Data
{
    public function __construct(
        public int $RelationType,
        #[Uuid]
        public ?string $ID = null,
        #[RequiredWithout('RelatedOperationName')]
        #[Uuid]
        public ?string $RelatedOperationID = null,
        #[RequiredWithout('RelatedOperationID')]
        #[Max(200)]
        public ?string $RelatedOperationName = null,
    ) {
    }
}
