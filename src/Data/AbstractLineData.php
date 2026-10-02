<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Concerns\HasProductFields;

/**
 * The fields the product line models of sale and purchase documents share, with the same types
 * and lengths in every table: the Sale Quote, Sale Order and Sale Invoice Line Models, and the
 * Purchase Order and Purchase Invoice Line Models. With them come the product fields every
 * object with a `ProductID` carries. Each model is a final child that adds its own fields
 * (`AverageCost` on sale lines, `Account` on invoice lines).
 *
 * Every one of those tables marks the seven constructor fields required, so each child takes
 * them through this constructor; the optional fields are set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractLineData extends Data
{
    use HasProductFields;

    public ?float $Discount = null;

    public ?float $Total = null;

    #[Max(256)]
    public ?string $Comment = null;

    public function __construct(
        #[Uuid]
        public string $ProductID,
        #[Max(50)]
        public string $SKU,
        #[Max(1024)]
        public string $Name,
        public float $Quantity,
        public float $Price,
        public float $Tax,
        #[Max(50)]
        public string $TaxRule,
    ) {
    }
}
