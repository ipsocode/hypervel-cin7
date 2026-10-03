<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Opportunity;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Opportunity, one entry of a response of its endpoint: the table with its `ID`. The bodies of POST
 * and PUT are `OpportunityPostData` and `OpportunityPutData`.
 *
 * @see docs/data.md
 */
final class OpportunityData extends AbstractOpportunityData implements WithResponse
{
    use HasResponse;

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
        public ?string $ID = null,
    ) {
        parent::__construct($CustomerName, $BillingAddressLine1, $Currency, $TaxRule, $Terms, $PriceTier, $OpportunityLocation, $CustomerCurrency, $TermMethod, $SalesRepresentative, $ShipToOther);
    }
}
