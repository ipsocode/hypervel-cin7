<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\FinishedGoods\Pick;

use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\AbstractStockLineData;

/**
 * Finished Goods Pick Line Model, a line of a finished goods pick: a product, found by `ProductID`
 * or `ProductCode`, with its `Quantity`. `Name`, `Unit` and `Cost` are read-only.
 *
 * @see docs/data.md
 */
final class FinishedGoodsPickLineData extends AbstractStockLineData
{
    public function __construct(
        float $Quantity,
        public ?string $Unit = null,
        public ?float $Cost = null,
    ) {
        parent::__construct($Quantity);
    }
}
