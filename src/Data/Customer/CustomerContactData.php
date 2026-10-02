<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Customer;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

/**
 * Customer Contact Model (the reference's Supplier/Customer Contact Model), one entry of a customer's `Contacts`.
 *
 * The reference's examples also carry `JobTitle` and `CustomerID` on each contact.
 *
 * @see docs/data.md
 */
final class CustomerContactData extends Data
{
    public function __construct(
        public string|Optional $ID,
        public string|Optional $CustomerID,
        public string|Optional $Name,
        public string|Optional|null $JobTitle,
        public string|Optional|null $Phone,
        public string|Optional|null $MobilePhone,
        public string|Optional|null $Fax,
        public string|Optional|null $Email,
        public string|Optional|null $Website,
        public string|Optional|null $Comment,
        public bool|Optional $Default,
        public bool|Optional $IncludeInEmail,
        public int|Optional $MarketingConsent,
    ) {
    }
}
