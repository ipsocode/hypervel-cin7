<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Order;

use Hypervel\Data\Attributes\Validation\Max;
use Ipsocode\Cin7\Data\AbstractLineData;

/**
 * Sale Order Line Model, a superset of the Sale Quote Line the reference's Sale Order table names:
 * the line fields of `AbstractLineData`, the sale's `AverageCost`, `BackorderQuantity` and
 * `DropShip`, and `Backorder`, which appears only in the examples.
 *
 * @see docs/data.md
 */
final class SaleOrderLineData extends AbstractLineData
{
    public ?float $AverageCost = null;

    public ?bool $DropShip = null;

    public ?bool $Backorder = null;

    public ?float $BackorderQuantity = null;

    public ?float $Total = null;

    #[Max(256)]
    public ?string $Comment = null;
}
