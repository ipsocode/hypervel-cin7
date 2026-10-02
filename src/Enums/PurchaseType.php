<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Enums;

/**
 * The type of a purchase, as the purchase list and purchase credit note list report it.
 *
 * @see docs/data.md
 */
enum PurchaseType: string
{
    case SimplePurchase = 'Simple Purchase';
    case AdvancedPurchase = 'Advanced Purchase';
    case ServicePurchase = 'Service Purchase';
    case PurchaseCreditNote = 'Purchase Credit Note';
    case CreditNote = 'Credit Note';
}
