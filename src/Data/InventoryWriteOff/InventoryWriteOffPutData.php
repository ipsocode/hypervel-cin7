<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\InventoryWriteOff;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * The body of `inventoryWriteOff` PUT: the Inventory Write-Off POST/PUT table with the `TaskID`
 * PUT requires. The POST body is `InventoryWriteOffPostData`.
 *
 * @see docs/data.md
 */
final class InventoryWriteOffPutData extends AbstractInventoryWriteOffData
{
    public function __construct(
        CompletionStatus $Status,
        string $Account,
        #[Uuid]
        public string $TaskID,
    ) {
        parent::__construct($Status, $Account);
    }
}
