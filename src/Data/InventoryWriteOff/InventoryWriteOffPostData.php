<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\InventoryWriteOff;

/**
 * The body of `inventoryWriteOff` POST: the Inventory Write-Off POST/PUT table without the
 * `TaskID` Cin7 assigns. A POST takes `DRAFT` or `COMPLETED`. The PUT body is
 * `InventoryWriteOffPutData`.
 *
 * @see docs/data.md
 */
final class InventoryWriteOffPostData extends AbstractInventoryWriteOffData
{
}
