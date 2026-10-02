<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Customer;

use Hypervel\Data\Data;

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
        public ?string $ID = null,
        public ?string $CustomerID = null,
        public ?string $Name = null,
        public ?string $JobTitle = null,
        public ?string $Phone = null,
        public ?string $MobilePhone = null,
        public ?string $Fax = null,
        public ?string $Email = null,
        public ?string $Website = null,
        public ?string $Comment = null,
        public ?bool $Default = null,
        public ?bool $IncludeInEmail = null,
        public ?int $MarketingConsent = null,
    ) {
    }
}
