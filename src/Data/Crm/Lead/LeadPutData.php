<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Crm\Lead;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\LeadStatus;

/**
 * The body of PUT: the table with the `ID` of the record to change, which PUT requires. The POST
 * body is `LeadPostData`.
 *
 * @see docs/data.md
 */
final class LeadPutData extends AbstractLeadData
{
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
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($LeadStatus, $Name, $Currency, $PaymentTerm, $PriceTier, $SalesRepresentative, $TaxRule, $CloseChance, $CloseDate);
    }
}
