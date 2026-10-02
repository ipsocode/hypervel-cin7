<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Customer;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
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
        #[Max(256)]
        public string $Name,
        #[Uuid]
        public ?string $ID = null,
        public ?string $CustomerID = null,
        public ?string $JobTitle = null,
        #[Max(50)]
        public ?string $Phone = null,
        #[Max(50)]
        public ?string $MobilePhone = null,
        #[Max(50)]
        public ?string $Fax = null,
        #[Max(256)]
        public ?string $Email = null,
        #[Max(256)]
        public ?string $Website = null,
        #[Max(256)]
        public ?string $Comment = null,
        public ?bool $Default = null,
        public ?bool $IncludeInEmail = null,
        public ?int $MarketingConsent = null,
    ) {
    }
}
