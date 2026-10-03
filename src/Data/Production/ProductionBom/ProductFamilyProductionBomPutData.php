<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Production\ProductionBom;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The body of `production/productionBOM` PUT for a product family: one BOM, which requires its
 * `BOMID` and `OutputQuantity`, with the `ProductFamilyID` it belongs to. The PUT example sends no
 * `BufferPercent`, `Version`, `Name` or `IsDefault`, so they are optional here.
 *
 * @see docs/data.md
 */
final class ProductFamilyProductionBomPutData extends AbstractProductFamilyProductionBomData
{
    public function __construct(
        float $OutputQuantity,
        #[Uuid]
        public string $BOMID,
        #[Uuid]
        public ?string $ProductFamilyID = null,
        public ?float $BufferPercent = null,
        public ?int $Version = null,
        #[Max(256)]
        public ?string $Name = null,
        public ?bool $IsDefault = null,
    ) {
        parent::__construct($OutputQuantity);
    }
}
