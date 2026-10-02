<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Opportunity;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * The fields of the Opportunity table: the response of its endpoint and the body of its POST and PUT.
 * Each is a final child that adds its `ID`, or none. Every opportunity needs its customer name, billing address line, currency, tax rule, terms, price tier, location, customer currency, term method, sales representative and ShipToOther, so each child passes them to this constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractOpportunityData extends Data
{
    #[Uuid]
    public ?string $CustomerID = null;

    #[Uuid]
    public ?string $LeadID = null;

    #[Max(256)]
    public ?string $Contact = null;

    #[Max(50)]
    public ?string $Phone = null;

    #[Max(50)]
    public ?string $DefaultAccount = null;

    #[Max(256)]
    public ?string $BillingAddressLine2 = null;

    public ?float $TaxPercent = null;

    public ?bool $TaxInclusive = null;

    public ?int $TermDays = null;

    #[Max(256)]
    public ?string $ShippingAddressLine1 = null;

    #[Max(256)]
    public ?string $ShippingAddressLine2 = null;

    #[Max(256)]
    public ?string $OpportunityNumber = null;

    #[DateTime]
    public ?string $OpportunityDate = null;

    #[Max(1024)]
    public ?string $OpportunityComment = null;

    #[Max(1024)]
    public ?string $OpportunityMemo = null;

    #[Max(25)]
    public ?string $OpportunityStatus = null;

    public ?float $TaxTotal = null;

    public ?float $Total = null;

    #[Max(256)]
    public ?string $CustomerReference = null;

    public ?float $CurrencyConversionRate = null;

    public ?int $TermDueNextMonth = null;

    #[Max(128)]
    public ?string $ShipToCompany = null;

    #[Max(512)]
    public ?string $ShipToContact = null;

    #[Max(64)]
    public ?string $ShipToCountry = null;

    #[Max(32)]
    public ?string $ShipToPostCode = null;

    #[Max(128)]
    public ?string $ShipToState = null;

    #[Max(128)]
    public ?string $ShipToCity = null;

    #[Max(256)]
    public ?string $ShipToAddress1 = null;

    #[Max(256)]
    public ?string $ShipToAddress2 = null;

    #[Max(256)]
    public ?string $CustomField1 = null;

    #[Max(256)]
    public ?string $CustomField2 = null;

    #[Max(256)]
    public ?string $CustomField3 = null;

    #[Max(256)]
    public ?string $CustomField4 = null;

    #[Max(256)]
    public ?string $CustomField5 = null;

    #[Max(256)]
    public ?string $CustomField6 = null;

    #[Max(256)]
    public ?string $CustomField7 = null;

    #[Max(256)]
    public ?string $CustomField8 = null;

    #[Max(256)]
    public ?string $CustomField9 = null;

    #[Max(256)]
    public ?string $CustomField10 = null;

    /**
     * @var null|list<OpportunityLineData>
     */
    #[DataCollectionOf(OpportunityLineData::class)]
    public ?array $Lines = null;

    /**
     * @var null|list<OpportunityAdditionalChargeData>
     */
    #[DataCollectionOf(OpportunityAdditionalChargeData::class)]
    public ?array $AdditionalCharges = null;

    public function __construct(
        #[Max(256)]
        public string $CustomerName,
        #[Max(256)]
        public string $BillingAddressLine1,
        #[Max(3)]
        public string $Currency,
        #[Max(255)]
        public string $TaxRule,
        #[Max(50)]
        public string $Terms,
        #[Max(50)]
        public string $PriceTier,
        #[Max(256)]
        public string $OpportunityLocation,
        #[Max(3)]
        public string $CustomerCurrency,
        public int $TermMethod,
        #[Max(256)]
        public string $SalesRepresentative,
        public bool $ShipToOther,
    ) {
    }
}
