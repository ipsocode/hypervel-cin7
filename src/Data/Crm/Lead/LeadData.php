<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Lead;

use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\LeadStatus;

/**
 * Lead, one entry of a response of its endpoint: the table with its `ID`, which the POST and PUT
 * response examples leave out, so it is nullable and `#[Required]`, which only a write body checks.
 * The bodies of POST and PUT are `LeadPostData` and `LeadPutData`.
 *
 * @see docs/data.md
 */
final class LeadData extends AbstractLeadData implements WithResponse
{
    use HasResponse;

    public function __construct(
        LeadStatus $LeadStatus,
        string $Name,
        string $Currency,
        string $PaymentTerm,
        string $PriceTier,
        string $SalesRepresentative,
        string $TaxRule,
        int $CloseChance,
        string $CloseDate,
        #[Required]
        #[Uuid]
        public ?string $ID = null,
    ) {
        parent::__construct($LeadStatus, $Name, $Currency, $PaymentTerm, $PriceTier, $SalesRepresentative, $TaxRule, $CloseChance, $CloseDate);
    }
}
