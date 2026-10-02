<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The kind of record a CRM task or workflow belongs to.
 *
 * @see docs/data.md
 */
enum TaskEntityType: string
{
    case Purchase = 'Purchase';
    case Sale = 'Sale';
    case Customer = 'Customer';
    case Supplier = 'Supplier';
    case Lead = 'Lead';
    case Opportunity = 'Opportunity';
    case Assembly = 'Assembly';
    case Disassembly = 'Disassembly';
    case Job = 'Job';
    case InventoryWriteOff = 'Inventory write off';
    case ExpenseClaimReceipt = 'Expense Claim Receipt';
    case ExpenseClaim = 'Expense Claim';
    case Journal = 'Journal';
    case Production = 'Production';
}
