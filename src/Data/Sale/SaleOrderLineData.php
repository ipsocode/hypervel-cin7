<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

/**
 * Sale Order Line Model, a superset of the Sale Quote Line the reference's Sale Order table names:
 * the shared line fields of `AbstractSaleLineData`, `BackorderQuantity` and `DropShip`, and `Backorder`,
 * which appears only in the examples.
 *
 * @see docs/data.md
 */
final class SaleOrderLineData extends AbstractSaleLineData
{
    public ?bool $DropShip = null;

    public ?bool $Backorder = null;

    public ?float $BackorderQuantity = null;
}
