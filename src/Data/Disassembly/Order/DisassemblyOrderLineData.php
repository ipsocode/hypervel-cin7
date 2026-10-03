<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Disassembly\Order;

use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\AbstractStockLineData;

/**
 * Disassembly Order Line Model, a component line of a disassembly order: a product, found by
 * `ProductID` or `ProductCode`, with its `Quantity` and `Cost`. `Name` and `Unit` are read-only.
 *
 * @see docs/data.md
 */
final class DisassemblyOrderLineData extends AbstractStockLineData
{
    public function __construct(
        float $Quantity,
        public float $Cost,
        public ?string $Unit = null,
        public ?string $Account = null,
    ) {
        parent::__construct($Quantity);
    }
}
