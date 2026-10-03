<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The kind of task a transaction belongs to.
 *
 * @see docs/data.md
 */
enum TransactionType: string
{
    case Purchase = 'Purchase';
    case Sale = 'Sale';
    case Moneyspend = 'MoneySpend';
    case Moneyreceive = 'MoneyReceive';
    case Banktransfer = 'BankTransfer';
    case Expenseclaimtask = 'ExpenseClaimTask';
    case Finishedgoods = 'FinishedGoods';
    case Inventorywriteoff = 'InventoryWriteOff';
    case Stocktake = 'StockTake';
    case Stockadjustment = 'StockAdjustment';
    case Journal = 'Journal';
    case Disassembly = 'Disassembly';
    case Depreciation = 'Depreciation';
}
