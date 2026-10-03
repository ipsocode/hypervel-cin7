<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Opportunity;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of PUT: the table with the `ID` of the record to change, which PUT requires. The POST
 * body is `OpportunityPostData`.
 *
 * @see docs/data.md
 */
final class OpportunityPutData extends AbstractOpportunityData
{
    public function __construct(
        string $CustomerName,
        string $BillingAddressLine1,
        string $Currency,
        string $TaxRule,
        string $Terms,
        string $PriceTier,
        string $OpportunityLocation,
        string $CustomerCurrency,
        int $TermMethod,
        string $SalesRepresentative,
        bool $ShipToOther,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($CustomerName, $BillingAddressLine1, $Currency, $TaxRule, $Terms, $PriceTier, $OpportunityLocation, $CustomerCurrency, $TermMethod, $SalesRepresentative, $ShipToOther);
    }
}
