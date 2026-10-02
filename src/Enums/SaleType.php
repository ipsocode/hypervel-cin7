<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The type of a sale, as the sale and sale list responses report it.
 *
 * @see docs/data.md
 */
enum SaleType: string
{
    case SimpleSale = 'Simple Sale';
    case AdvancedSale = 'Advanced Sale';
    case ServiceSale = 'Service Sale';
    case SaleCreditNote = 'Sale Credit Note';
    case CreditNote = 'Credit Note';
}
