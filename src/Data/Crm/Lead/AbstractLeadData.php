<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Lead;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\Other\CustomerAddressData;
use Ipsocode\Cin7\Data\Other\CustomerContactData;
use Ipsocode\Cin7\Enums\LeadStatus;

/**
 * The fields of the Lead table: the response of its endpoint and the body of its POST and PUT.
 * Each is a final child that adds its `ID`, or none. Every lead needs its status, name, currency, payment term, price tier, sales representative, tax rule, close chance and close date, so each child passes them to this constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractLeadData extends Data
{
    public ?float $Amount = null;

    #[Max(2000)]
    public ?string $Comments = null;

    /**
     * @var null|list<CustomerContactData>
     */
    #[DataCollectionOf(CustomerContactData::class)]
    public ?array $Contacts = null;

    /**
     * @var null|list<CustomerAddressData>
     */
    #[DataCollectionOf(CustomerAddressData::class)]
    public ?array $Addresses = null;

    public function __construct(
        public LeadStatus $LeadStatus,
        #[Max(256)]
        public string $Name,
        #[Max(3)]
        public string $Currency,
        public string $PaymentTerm,
        #[Max(50)]
        public string $PriceTier,
        #[Max(256)]
        public string $SalesRepresentative,
        #[Max(255)]
        public string $TaxRule,
        public int $CloseChance,
        #[DateTime]
        public string $CloseDate,
    ) {
    }
}
